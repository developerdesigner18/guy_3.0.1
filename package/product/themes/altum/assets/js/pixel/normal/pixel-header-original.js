
let send_data = (data, callback, async = true) => {
    try {

        /* Add the timestamp to the request */
        data['timestamp'] = new Date().getTime() / 1000;

        let xhr = new XMLHttpRequest;
        xhr.onload = function (event) {
            if (xhr.readyState === 4) {
                try {
                    let response = JSON.parse(xhr.responseText);

                    /* Send the response to the callback */
                    if(callback) {
                        callback(response);
                    }
                } catch (error) {
                    /* :) */
                }
            }
        };
        xhr.open('POST', `${pixel_url_base}pixel-track/${pixel_key}`, async);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.send(JSON.stringify(data));

    } catch (error) {
        console.log(`Analytics pixel: ${error}`);
    }
};

let send_data_beacon = data => {
    try {
        /* Add the timestamp to the request */
        data['timestamp'] = new Date().getTime() / 1000;

        navigator.sendBeacon(`${pixel_url_base}pixel-track/${pixel_key}`, JSON.stringify(data));

    } catch (error) {
        console.log(`Analytics pixel: ${error}`);
    }
};

/* Visitor class */
class AltumCodeVisitor {

    /* Create and initiate the class with the proper parameters */
    constructor() {

        /* Check if we already have the visitor id */
        if(localStorage.getItem(get_dynamic_var('visitor_uuid')) && localStorage.getItem(get_dynamic_var('visitor_uuid')).trim() != '') {
            this.visitor_uuid = localStorage.getItem(get_dynamic_var('visitor_uuid')).trim();

            let custom_parameters = this.get_custom_parameters();

            /* If custom parameters are set after the initiation of the visitor, update the visitor with the new parameters */
            if(
                custom_parameters && (
                    !localStorage.getItem(get_dynamic_var('visitor_custom_parameters')) ||
                    (localStorage.getItem(get_dynamic_var('visitor_custom_parameters')) && localStorage.getItem(get_dynamic_var('visitor_custom_parameters')) != btoa(JSON.stringify(custom_parameters)))
                )
            ) {
                /* Generate the details for the visitor */
                let details = this.get_extra_details();

                /* Send the details */
                send_data({
                    visitor_uuid: this.visitor_uuid,
                    type: 'initiate_visitor',
                    data: details
                }, null, false);
            }

        } else {

            /* Generate it randomly */
            let visitor_uuid = get_random_id();

            this.visitor_uuid = visitor_uuid;

            /* Save it to localstorage */
            localStorage.setItem(get_dynamic_var('visitor_uuid'), this.visitor_uuid);

            /* Generate the details for the visitor */
            let details = this.get_extra_details();

            /* Send the details */
            send_data({
                visitor_uuid,
                type: 'initiate_visitor',
                data: details
            }, null, false);
        }

    }

    get_extra_details() {

        let data = {
            resolution: {
                width: window.screen.width,
                height: window.screen.height
            },

            /* Extra detection based on the browser is made directly on the server side */
        };

        let custom_parameters = this.get_custom_parameters();

        if(custom_parameters) {
            data.custom_parameters = custom_parameters;

            /* Save it to localstorage */
            localStorage.setItem(get_dynamic_var('visitor_custom_parameters'), btoa(JSON.stringify(custom_parameters)));
        }

        return data;
    }

    get_custom_parameters() {
        /* Check for extra parameters */
        let this_script = document.querySelector(`script[src$="pixel/${pixel_key}"]`);

        if(this_script.dataset.customParameters) {

            try {
                let custom_parameters = JSON.parse(this_script.dataset.customParameters);

                return custom_parameters;

            } catch(error) {
                return false;
            }

        } else {

            return false;

        }
    }

}


/* Session class */
class AltumCodeEvents {

    /* Create and initiate the class with the proper parameters */
    constructor() {

        this.visitor_uuid = localStorage.getItem(get_dynamic_var('visitor_uuid'));
        this.visitor_session_uuid = localStorage.getItem(get_dynamic_var('visitor_session_uuid'));

        /* Store UUID of the last event for page loads */
        localStorage.setItem(get_dynamic_var('visitor_session_event_uuid'), get_random_id());
        this.visitor_session_event_uuid = localStorage.getItem(get_dynamic_var('visitor_session_event_uuid'));

        /* Get the session date if existing to detect the current session */
        let visitor_session_date = localStorage.getItem(get_dynamic_var('visitor_session_date'));
        let date = new Date();

        /* Check if the current page is the first for this session */
        if(!visitor_session_date || (visitor_session_date && date - (new Date(visitor_session_date)) > 30*60*1000)) {

            /* Generate the session uuid */
            this.visitor_session_uuid = get_random_id();
            localStorage.setItem(get_dynamic_var('visitor_session_uuid'), this.visitor_session_uuid);

            /* Emit the landing page event */
            this.event_landing_page();

        } else {

            /* Emit the pageview event */
            this.event_pageview()

        }

        /* Set the new session date */
        localStorage.setItem(get_dynamic_var('visitor_session_date'), date.toJSON());

        /* Expose function to window */
        window[pixel_exposed_identifier] = {

            goal: (key) => {
                this.event_goal_conversion(key);
            }

        };

        /* Initiate event handlers */
        if(pixel_track_events_children) {
            this.initiate_event_handlers();
        }

        /* Goals tracking if needed */
        if(pixel_goals.length) {
            let current_domain = get_current_url_domain_no_www();

            /* Iterate on all goals and initiate them if needed */
            for(let goal of pixel_goals) {

                /* Check if goal url matches the current url */
                if(goal.type == 'pageview' && (goal.url == current_domain || goal.url == 'www.'+current_domain)) {

                    this.event_goal_conversion(goal.key);

                }
            }
        }

        /* Events array to be used by heatmaps and session recordings */
        let events = [];
        let events_tracking_initiated = false;

        /* Initiate heatmaps tracking if needed */
        if(pixel_heatmaps.length) {
            let device = get_device_type();
            let current_domain = get_current_url_domain_no_www();

            /* Iterate on all heatmaps and initiate them if needed */
            for(let heatmap of pixel_heatmaps) {

                /* Check if heatmap url matches the current url */
                if(heatmap.url == current_domain || heatmap.url == 'www.'+current_domain) {

                    /* If needed, snapshot the page and send the data */
                    if(!heatmap[`snapshot_id_${device}`]) {

                        let rrweb_stop = rrwebRecord({
                            emit(event) {
                                events_tracking_initiated = true;

                                /* Push events here */
                                events.push(event);

                                /* Send the snapshot data */
                                if(events.length == 2 && events[0].type == 4 && events[1].type == 2) {

                                    // if(!pixel_track_sessions_replays) {
                                    //     rrweb_stop();
                                    // }

                                    /* Send the caught snapshot */
                                    send_data({
                                        type: 'heatmap_snapshot',
                                        heatmap_id: heatmap.heatmap_id,
                                        data: events
                                    });
                                }
                            },
                        });
                    }

                    /* Initiate the events handlers for heatmaps */
                    this.initiate_event_handler_click(heatmap.heatmap_id);
                    // this.initiate_event_handler_scroll(heatmap.heatmap_id);

                    /* No need to continue the loop if found the heatmap */
                    break;
                }
            }
        }

        /* Session replay tracking */
        if(pixel_track_sessions_replays) {

            if(!events_tracking_initiated) {
                rrwebRecord({
                    emit(event) {
                        events.push(event);
                    },
                });
            }

            let send_sessions_replays = () => {

                if(events.length) {
                    send_data({
                        visitor_uuid: this.visitor_uuid,
                        visitor_session_uuid: this.visitor_session_uuid,
                        visitor_session_event_uuid: this.visitor_session_event_uuid,
                        type: 'replays',
                        data: events
                    });

                    events = [];
                }

            };

            setInterval(send_sessions_replays, 1000);

            /* Timer for the click so we dont spam the server */
            let timer = false;

            document.addEventListener('click', event => {

                /* Make sure the event was fired by the actual user and not programatically */
                if(!event.isTrusted) {
                    return false;
                }

                /* Timeout depending on the element that has been clicked so that we can detect actual url changes clicks */
                let timeout = event.target.tagName == 'A' && !event.target.getAttribute('href').startsWith('#') ? 0 : 500;

                timer = setTimeout(() => send_sessions_replays, timeout);

            });

            document.querySelectorAll('form').forEach(form_element => {

                form_element.addEventListener('submit', send_sessions_replays);

            });

            /* On page changes */
            const termination_event = 'onpagehide' in self ? 'pagehide' : 'unload';

            window.addEventListener(termination_event, send_sessions_replays, {capture: true});
        }

    }

    initiate_event_handlers() {

        this.initiate_event_handler_click();

        this.initiate_event_handler_scroll();

        this.initiate_event_handler_forms();

        this.initiate_event_handler_resize();
    }

    /* Mouse click event handler */
    initiate_event_handler_click(heatmap_id = null) {
        /* Last click data for comparison */
        let click_data = '';

        /* Count of the clicks when the click is simply the same */
        let clicks_count = 1;

        /* Timer for the click so we dont spam the server */
        let timer = false;

        document.addEventListener('click', event => {

            /* Make sure the event was fired by the actual user and not programatically */
            if(!event.isTrusted) {
                return false;
            }

            /* Timeout depending on the element that has been clicked so that we can detect actual url changes clicks */
            let timeout = event.target.tagName == 'A' && !event.target.getAttribute('href').startsWith('#') ? 0 : 500;

            /* Get the text of the area that the user clicked */
            let text = event.target.innerText.length > 61 ? `${event.target.innerText.substr(0, 61)}...` : event.target.innerText;

            let data = {
                mouse: {
                    x: event.pageX,
                    y: event.pageY
                },

                text: text,
                element: event.target.tagName.toLowerCase()
            };


            /* Check if the click is the same with the previous */
            if(JSON.stringify(data) == click_data) {
                clicks_count++;

                clearInterval(timer);
            }

            click_data = JSON.stringify(data);

            timer = setTimeout(() => {

                this.event_child('click', data, clicks_count, heatmap_id);

                clicks_count = 1;

            }, timeout);

        });
    }

    /* Scroll event handler */
    initiate_event_handler_scroll(heatmap_id = null) {

        /* Timer for the scroll so we dont spam the server */
        let timer = false;

        document.addEventListener('scroll', event => {

            /* Make sure the event was fired by the actual user and not programatically */
            if(!event.isTrusted) {
                return false;
            }

            let data = {
                scroll: {
                    percentage: parseInt((document.documentElement['scrollTop'] || document.body['scrollTop']) / ((document.documentElement['scrollHeight'] || document.body['scrollHeight']) - document.documentElement.clientHeight) * 100)

                    // Do not store the top value, store the percentage of scrolling instead
                    // top: window.pageYOffset || document.documentElement.scrollTop,

                    // Most websites do not have a horizontal scroll
                    // left: window.pageXOffset || document.documentElement.scrollLeft
                }
            };


            clearInterval(timer);

            timer = setTimeout(() => {

                this.event_child('scroll', data, 1, heatmap_id);

            }, 500);

        });
    }

    /* Inputs event handler */
    initiate_event_handler_forms() {

        let event_handler_form = event => {

            /* Store data from the form */
            let data = {
                form: {

                }
            };

            // INPUT VALUES ARE NOT STORED ANYMORE FOR PRIVACY REASONS
            // let form_element = event.target;
            //
            // /* Parse all the input fields */
            // form_element.querySelectorAll('input').forEach(input_element => {
            //
            //     if(input_element.type == 'password' || input_element.type == 'hidden') {
            //         return;
            //     }
            //
            //     if(input_element.name.indexOf('captcha') !== -1) {
            //         return;
            //     }
            //
            //     data.form[input_element.name] = input_element.value;
            //
            // });

            /* Submit the event */
            this.event_child('form', data);

        };

        /* Make sure to know all of the form submissions on the page */
        document.querySelectorAll('form').forEach(form_element => {

            form_element.addEventListener('submit', event_handler_form);

        });

    }

    /* Window resize event handler */
    initiate_event_handler_resize() {

        /* Timer for the scroll so we dont spam the server */
        let timer = false;

        window.addEventListener('resize', event => {

            /* Make sure the event was fired by the actual user and not programatically */
            if(!event.isTrusted) {
                return false;
            }

            let data = {
                viewport: this.get_viewport()
            };


            clearInterval(timer);

            timer = setTimeout(() => {

                this.event_child('resize', data);

            }, 500);

        });

    }


    event_landing_page() {

        let data = {
            path: window.location.pathname,
            title: document.title,
            referrer: document.referrer.includes(`${location.protocol}//${location.host}${location.pathname}`) ? null : document.referrer,

            utm: this.get_utm_params(),

            viewport: this.get_viewport()
        };

        this.send_data({
            visitor_uuid: this.visitor_uuid,
            visitor_session_uuid: this.visitor_session_uuid,
            visitor_session_event_uuid: this.visitor_session_event_uuid,
            type: 'landing_page',
            data
        });

    }

    event_pageview() {

        let data = {
            path: window.location.pathname,
            title: document.title,
            referrer: document.referrer.includes(`${location.protocol}//${location.host}${location.pathname}`) ? null : document.referrer,

            viewport: this.get_viewport()
        };

        this.send_data({
            visitor_uuid: this.visitor_uuid,
            visitor_session_uuid: this.visitor_session_uuid,
            visitor_session_event_uuid: this.visitor_session_event_uuid,
            type: 'pageview',
            data
        });

    }

    event_child(type, data = {}, count = 1, heatmap_id = null) {

        send_data_beacon({
            visitor_uuid: this.visitor_uuid,
            visitor_session_uuid: this.visitor_session_uuid,
            visitor_session_event_uuid: this.visitor_session_event_uuid,
            type,
            data,
            count,
            heatmap_id
        });

    }

    event_goal_conversion(key) {

        /* Iterate on all goals and initiate them if needed */
        for(let goal of pixel_goals) {

            /* Check if goal url matches the current url */
            if(goal.key == key && !localStorage.getItem(get_dynamic_var(`visitor_goal_${goal.key}`))) {

                /* Send the goal completion */
                send_data({
                    visitor_uuid: this.visitor_uuid,
                    visitor_session_uuid: this.visitor_session_uuid,
                    visitor_session_event_uuid: this.visitor_session_event_uuid,
                    type: 'goal_conversion',
                    goal_key: goal.key
                });

                /* Set it in the local storage to make sure to not send it again */
                localStorage.setItem(get_dynamic_var(`visitor_goal_${goal.key}`), true);

                break;
            }

        }
    }

    send_data(data) {

        send_data(data, (response) => {

            /* Handle potential needs to refresh the visitor or session */
            if(response && response.details && response.details.refresh) {

                switch(response.details.refresh) {
                    case 'visitor':

                        localStorage.removeItem(get_dynamic_var('visitor_uuid'));

                        let altumcodevisitor = new AltumCodeVisitor();

                        break;

                    case 'session':

                        localStorage.removeItem(get_dynamic_var('visitor_session_uuid'));
                        localStorage.removeItem(get_dynamic_var('visitor_session_date'));

                        let altumcodeevents = new AltumCodeEvents();

                        break;
                }

            }
        });

    }

    get_viewport() {
        return {
            width: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
            height: window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
        }
    }

    get_utm_params() {
        let url_params = new URLSearchParams(window.location.search);

        return {
            source: url_params.get('utm_source'),
            medium: url_params.get('utm_medium'),
            campaign: url_params.get('utm_campaign'),
            term: url_params.get('utm_term'),
            content: url_params.get('utm_content'),
        };
    }

}
