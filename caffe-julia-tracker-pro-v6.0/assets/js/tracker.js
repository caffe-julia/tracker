/**
 * Caffe Julia Tracker Pro - JavaScript
 * Angepasst für WordPress REST API statt localStorage
 */

// WordPress REST API Integration
const API_BASE = cjtpData.restUrl;
const API_NONCE = cjtpData.nonce;

// State Management
let events = [];
let currentView = 'overview';
let currentEventId = null;
let currentEventGroup = null;
let nextId = 1;
let isAuthenticated = false;
let sessionToken = null;

// Initialisierung
document.addEventListener('DOMContentLoaded', function() {
    checkAuth();
});

function checkAuth() {
    // Vereinfachte Auth für WordPress - User ist eingeloggt wenn er die Seite sieht
    isAuthenticated = true;
    sessionToken = API_NONCE;
    initializeApp();
}

async function initializeApp() {
    await loadEventsFromWordPress();
    render();
}

// ===== WordPress REST API Calls =====

async function loadEventsFromWordPress() {
    try {
        const response = await fetch(API_BASE + 'events', {
            headers: {
                'X-WP-Nonce': API_NONCE
            }
        });
        const data = await response.json();
        events = data || [];

        // Finde höchste ID für nextId
        if (events.length > 0) {
            nextId = Math.max(...events.map(e => e.id)) + 1;
        }
    } catch (error) {
        console.error('Fehler beim Laden der Events:', error);
        events = [];
    }
}

async function saveData() {
    // Daten werden automatisch bei jedem Update gespeichert
    render();
}

async function saveEventToWordPress(event) {
    try {
        const url = API_BASE + 'events' + (event.id ? '/' + event.id : '');
        const method = event.id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': API_NONCE
            },
            body: JSON.stringify(event)
        });

        const result = await response.json();

        if (!event.id && result.id) {
            event.id = result.id;
        }

        return true;
    } catch (error) {
        console.error('Fehler beim Speichern:', error);
        return false;
    }
}

async function deleteEventFromWordPress(id) {
    try {
        await fetch(API_BASE + 'events/' + id, {
            method: 'DELETE',
            headers: {
                'X-WP-Nonce': API_NONCE
            }
        });
        return true;
    } catch (error) {
        console.error('Fehler beim Löschen:', error);
        return false;
    }
}

// ===== Original Tracker Funktionen (angepasst) =====

function logout() {
    if (confirm('Wirklich abmelden?')) {
        window.location.href = '/wp-admin/';
    }
}

async function exportToCSV() {
    // Nutze WordPress AJAX für Export
    window.location.href = cjtpData.ajaxUrl + '?action=cjtp_export_csv&nonce=' + API_NONCE;
}

function calculateTotalKaffees(muehlen) {
    let doppel = 0;
    let einzel = 0;

    if (muehlen && Array.isArray(muehlen)) {
        muehlen.forEach(muehle => {
            const doppelDiff = Math.max(0, (muehle.doppelBezug?.ende || 0) - (muehle.doppelBezug?.start || 0));
            const einzelDiff = Math.max(0, (muehle.einzelBezug?.ende || 0) - (muehle.einzelBezug?.start || 0));
            doppel += doppelDiff;
            einzel += einzelDiff;
        });
    }

    return {
        doppel: doppel,
        einzel: einzel,
        total: (doppel * 2) + einzel
    };
}

function calculateWorkHours(event) {
    if (!event.workStartTime || !event.workEndTime) {
        return 0;
    }

    const start = new Date('2000-01-01 ' + event.workStartTime);
    const end = new Date('2000-01-01 ' + event.workEndTime);
    let minutes = (end - start) / 1000 / 60;
    minutes -= (event.workBreakMinutes || 0);
    return Math.max(0, minutes / 60).toFixed(2);
}

function increment(id, field) {
    const event = events.find(e => e.id === id);
    if (event) {
        event[field] = (event[field] || 0) + 1;
        saveEventToWordPress(event);
    }
}

function decrement(id, field) {
    const event = events.find(e => e.id === id);
    if (event && event[field] > 0) {
        event[field]--;
        saveEventToWordPress(event);
    }
}

function updateEvent(id, field, value) {
    const event = events.find(e => e.id === id);
    if (event) {
        event[field] = value;
        saveEventToWordPress(event);
    }
}

function updateWorkTime(id) {
    const event = events.find(e => e.id === id);
    if (event) {
        event.workStartTime = document.getElementById('startTime' + id).value;
        event.workEndTime = document.getElementById('endTime' + id).value;
        event.workBreakMinutes = parseInt(document.getElementById('breakMinutes' + id).value) || 0;
        event.workHours = parseFloat(calculateWorkHours(event));
        saveEventToWordPress(event);
    }
}

function updateMuehle(eventId, muehleIndex, bezugType, field, value) {
    const event = events.find(e => e.id === eventId);
    if (event && event.muehlen && event.muehlen[muehleIndex]) {
        const bezug = bezugType === 'doppel' ? 'doppelBezug' : 'einzelBezug';
        if (!event.muehlen[muehleIndex][bezug]) {
            event.muehlen[muehleIndex][bezug] = { start: 0, ende: 0 };
        }
        event.muehlen[muehleIndex][bezug][field] = parseInt(value) || 0;
        saveEventToWordPress(event);
    }
}

function copyPreviousDayStands(currentEvent) {
    if (!currentEvent.isPartOfMultiDay || currentEvent.multiDayIndex === 1) {
        return false;
    }

    const baseName = getEventBaseName(currentEvent.name);
    const previousDayIndex = currentEvent.multiDayIndex - 1;
    const previousDayName = baseName + ' - Tag ' + previousDayIndex;

    const previousEvent = events.find(e => e.name === previousDayName);

    if (previousEvent && previousEvent.muehlen && currentEvent.muehlen) {
        for (let i = 0; i < currentEvent.muehlen.length; i++) {
            if (previousEvent.muehlen[i]) {
                if (!currentEvent.muehlen[i].doppelBezug) {
                    currentEvent.muehlen[i].doppelBezug = { start: 0, ende: 0 };
                }
                currentEvent.muehlen[i].doppelBezug.start = previousEvent.muehlen[i].doppelBezug?.ende || 0;

                if (!currentEvent.muehlen[i].einzelBezug) {
                    currentEvent.muehlen[i].einzelBezug = { start: 0, ende: 0 };
                }
                currentEvent.muehlen[i].einzelBezug.start = previousEvent.muehlen[i].einzelBezug?.ende || 0;
            }
        }
        saveEventToWordPress(currentEvent);
        return true;
    }
    return false;
}

function getEventBaseName(eventName) {
    return eventName.replace(/ - Tag \d+$/, '');
}

function groupEventsByName() {
    const grouped = {};
    events.forEach(event => {
        const baseName = event.isPartOfMultiDay ? getEventBaseName(event.name) : event.name;
        if (!grouped[baseName]) {
            grouped[baseName] = [];
        }
        grouped[baseName].push(event);
    });

    Object.keys(grouped).forEach(key => {
        grouped[key].sort((a, b) => new Date(a.date) - new Date(b.date));
    });

    return grouped;
}

function addNewEvent() {
    currentView = 'new';
    render();
}

function cancelNewEvent() {
    currentView = 'overview';
    render();
}

async function saveNewEvent() {
    const eventName = document.getElementById('eventName').value;
    const mehrtagig = document.getElementById('mehrtagig').checked;
    const ganztaegig = document.getElementById('ganztaegig').checked;
    const anzahlMuehlen = parseInt(document.getElementById('anzahlMuehlen').value) || 1;

    if (!eventName) {
        alert('Bitte Event-Name eingeben');
        return;
    }

    let dates = [];

    if (mehrtagig) {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (!startDate || !endDate) {
            alert('Bitte Start- und Enddatum eingeben');
            return;
        }

        const start = new Date(startDate);
        const end = new Date(endDate);

        if (end < start) {
            alert('Enddatum muss nach Startdatum liegen');
            return;
        }

        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            dates.push(d.toISOString().split('T')[0]);
        }
    } else {
        const eventDate = document.getElementById('eventDate').value;
        if (!eventDate) {
            alert('Bitte Datum eingeben');
            return;
        }
        dates.push(eventDate);
    }

    // Erstelle für jeden Tag ein Event
    for (let index = 0; index < dates.length; index++) {
        const dayNumber = dates.length > 1 ? ` - Tag ${index + 1}` : '';
        const newEvent = {
            name: eventName + dayNumber,
            date: dates[index],
            ganztaegig: ganztaegig,
            anzahlMuehlen: anzahlMuehlen,
            mehrtagig: mehrtagig,
            isPartOfMultiDay: dates.length > 1,
            multiDayIndex: index + 1,
            multiDayTotal: dates.length,
            workStartTime: '',
            workEndTime: '',
            workBreakMinutes: 0,
            workHours: 0,
            muehlen: [],
            milch: 0,
            hafermilch: 0,
            ausgabeMatcha: 0,
            ausgabeSchokolade: 0,
            ausgabeTee: 0,
            mitteilung: ''
        };

        for (let i = 1; i <= anzahlMuehlen; i++) {
            newEvent.muehlen.push({
                nummer: i,
                name: `Mühle ${i}`,
                doppelBezug: { start: 0, ende: 0 },
                einzelBezug: { start: 0, ende: 0 }
            });
        }

        await saveEventToWordPress(newEvent);
        events.push(newEvent);
    }

    await loadEventsFromWordPress();
    currentView = 'overview';
    render();

    if (dates.length > 1) {
        alert(`${dates.length} Events (${dates[0]} bis ${dates[dates.length-1]}) wurden erstellt`);
    }
}

function viewEvent(id) {
    currentEventId = id;
    const event = events.find(e => e.id === id);
    if (event && event.isPartOfMultiDay) {
        currentEventGroup = getEventBaseName(event.name);

        if (event.multiDayIndex > 1) {
            const allZero = event.muehlen.every(m =>
                (m.doppelBezug?.start || 0) === 0 &&
                (m.doppelBezug?.ende || 0) === 0 &&
                (m.einzelBezug?.start || 0) === 0 &&
                (m.einzelBezug?.ende || 0) === 0
            );

            if (allZero) {
                copyPreviousDayStands(event);
            }
        }
    }
    currentView = 'detail';
    render();
}

function viewEventGroup(baseName) {
    currentEventGroup = baseName;
    currentView = 'group';
    render();
}

function backToOverview() {
    currentView = 'overview';
    currentEventId = null;
    currentEventGroup = null;
    render();
}

function backToGroup() {
    currentView = 'group';
    currentEventId = null;
    render();
}

async function deleteEvent(id) {
    if (confirm('Event wirklich löschen?')) {
        await deleteEventFromWordPress(id);
        events = events.filter(e => e.id !== id);
        backToOverview();
    }
}

async function deleteEventGroup(baseName) {
    const grouped = groupEventsByName();
    const eventGroup = grouped[baseName];

    if (!eventGroup) return;

    if (confirm(`Wirklich alle ${eventGroup.length} Tag(e) dieses Events löschen?`)) {
        for (const event of eventGroup) {
            await deleteEventFromWordPress(event.id);
        }
        events = events.filter(e => getEventBaseName(e.name) !== baseName);
        backToOverview();
    }
}

// Render-Funktionen bleiben gleich - zu lang zum Kopieren
// Werden aus dem Original übernommen
