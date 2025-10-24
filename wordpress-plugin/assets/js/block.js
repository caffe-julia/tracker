/**
 * Gutenberg Block für Caffe Julia Tracker
 */

(function(blocks, element, components, blockEditor) {
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var ToggleControl = components.ToggleControl;
    var SelectControl = components.SelectControl;

    registerBlockType('caffe-julia-tracker/tracker', {
        title: 'Caffe Julia Tracker',
        icon: 'chart-bar',
        category: 'widgets',
        attributes: {
            height: {
                type: 'string',
                default: '800px'
            },
            showStats: {
                type: 'boolean',
                default: true
            },
            theme: {
                type: 'string',
                default: 'light'
            }
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            function onChangeHeight(newHeight) {
                setAttributes({ height: newHeight });
            }

            function onChangeShowStats(newValue) {
                setAttributes({ showStats: newValue });
            }

            function onChangeTheme(newTheme) {
                setAttributes({ theme: newTheme });
            }

            return [
                el(InspectorControls, { key: 'controls' },
                    el(PanelBody, { title: 'Tracker-Einstellungen', initialOpen: true },
                        el(TextControl, {
                            label: 'Höhe',
                            value: attributes.height,
                            onChange: onChangeHeight,
                            help: 'z.B. 800px oder 100vh'
                        }),
                        el(ToggleControl, {
                            label: 'Statistiken anzeigen',
                            checked: attributes.showStats,
                            onChange: onChangeShowStats
                        }),
                        el(SelectControl, {
                            label: 'Theme',
                            value: attributes.theme,
                            options: [
                                { label: 'Hell', value: 'light' },
                                { label: 'Dunkel', value: 'dark' }
                            ],
                            onChange: onChangeTheme
                        })
                    )
                ),

                el('div', {
                    key: 'block-preview',
                    className: 'cjt-block-preview',
                    style: {
                        background: '#f0f0f1',
                        border: '2px dashed #8c8f94',
                        borderRadius: '4px',
                        padding: '40px',
                        textAlign: 'center'
                    }
                },
                    el('div', {
                        style: {
                            fontSize: '48px',
                            marginBottom: '20px'
                        }
                    }, '☕'),
                    el('h3', {
                        style: {
                            margin: '0 0 10px 0',
                            color: '#1e1e1e'
                        }
                    }, 'Caffe Julia Tracker'),
                    el('p', {
                        style: {
                            margin: '0 0 20px 0',
                            color: '#757575'
                        }
                    }, 'Der Tracker wird hier auf der Live-Seite angezeigt'),
                    el('div', {
                        style: {
                            display: 'inline-block',
                            background: '#fff',
                            padding: '10px 20px',
                            borderRadius: '4px',
                            fontSize: '14px'
                        }
                    },
                        el('strong', null, 'Einstellungen:'),
                        el('div', { style: { marginTop: '10px', textAlign: 'left' } },
                            'Höhe: ' + attributes.height,
                            el('br'),
                            'Statistiken: ' + (attributes.showStats ? 'Ja' : 'Nein'),
                            el('br'),
                            'Theme: ' + (attributes.theme === 'light' ? 'Hell' : 'Dunkel')
                        )
                    )
                )
            ];
        },

        save: function() {
            // Server-Side Rendering verwenden
            return null;
        }
    });

    // Statistik-Block
    registerBlockType('caffe-julia-tracker/statistics', {
        title: 'Caffe Julia Statistiken',
        icon: 'chart-line',
        category: 'widgets',
        attributes: {
            period: {
                type: 'number',
                default: 30
            },
            layout: {
                type: 'string',
                default: 'grid'
            }
        },

        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            function onChangePeriod(newPeriod) {
                setAttributes({ period: parseInt(newPeriod) });
            }

            function onChangeLayout(newLayout) {
                setAttributes({ layout: newLayout });
            }

            return [
                el(InspectorControls, { key: 'controls' },
                    el(PanelBody, { title: 'Statistik-Einstellungen', initialOpen: true },
                        el(TextControl, {
                            label: 'Zeitraum (Tage)',
                            type: 'number',
                            value: attributes.period,
                            onChange: onChangePeriod,
                            help: 'Anzahl der Tage für die Statistik'
                        }),
                        el(SelectControl, {
                            label: 'Layout',
                            value: attributes.layout,
                            options: [
                                { label: 'Raster', value: 'grid' },
                                { label: 'Liste', value: 'list' }
                            ],
                            onChange: onChangeLayout
                        })
                    )
                ),

                el('div', {
                    key: 'block-preview',
                    className: 'cjt-block-preview',
                    style: {
                        background: '#f0f0f1',
                        border: '2px dashed #8c8f94',
                        borderRadius: '4px',
                        padding: '30px',
                        textAlign: 'center'
                    }
                },
                    el('div', {
                        style: {
                            fontSize: '36px',
                            marginBottom: '15px'
                        }
                    }, '📊'),
                    el('h3', {
                        style: {
                            margin: '0 0 10px 0',
                            color: '#1e1e1e'
                        }
                    }, 'Tracker Statistiken'),
                    el('p', {
                        style: {
                            margin: '0',
                            color: '#757575'
                        }
                    }, 'Zeigt Statistiken der letzten ' + attributes.period + ' Tage')
                )
            ];
        },

        save: function() {
            return null;
        }
    });

})(
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.blockEditor
);
