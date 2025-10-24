// Original Tracker JavaScript - angepasst für WordPress
// localStorage wurde durch WordPress REST API ersetzt

const API_BASE = cjtpData.restUrl;
const API_NONCE = cjtpData.nonce;

let events = [];
let currentView = 'overview';
let currentEventId = null;
let currentEventGroup = null;
let nextId = 1;

// Initialisierung
document.addEventListener('DOMContentLoaded', async function() {
    await loadEvents();
    render();
});

async function loadEvents() {
    try {
        const response = await fetch(API_BASE + 'events', {
            headers: { 'X-WP-Nonce': API_NONCE }
        });
        events = await response.json() || [];
        if (events.length > 0) {
            nextId = Math.max(...events.map(e => e.id)) + 1;
        }
    } catch (error) {
        console.error('Fehler:', error);
        events = [];
    }
}

async function saveEvent(event) {
    const url = API_BASE + 'events' + (event.id > 0 ? '/' + event.id : '');
    const method = event.id > 0 ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': API_NONCE
            },
            body: JSON.stringify(event)
        });
        const result = await response.json();
        if (!event.id && result.id) event.id = result.id;
        return true;
    } catch (error) {
        console.error('Fehler:', error);
        return false;
    }
}

// Restliche Funktionen aus dem Original werden hier eingefügt
