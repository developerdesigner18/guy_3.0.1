/* Start function */
let altumcodestart = () => {

    pixel_verify();

    let this_script = document.querySelector(`script[src$="pixel/${pixel_key}"]`);

    /* Check for DNT */
    let is_dnt = is_do_not_track();

    /* Check if we should supress the DNT or not */
    let should_track = !is_dnt || (is_dnt && this_script.dataset.ignoreDnt);

    if(should_track) {

        /* Initiate the Events */
        let altumcodeevents = new AltumCodeEvents();

    } else {

        if(is_dnt) {
            console.log(`${pixel_url_base}: ${pixel_key_dnt_message}`);
        }

    }
};

let altumcodeprestart = () => {
    altumcodestart();
}

/* Make sure the page is fully loaded before initiating */
if(document.readyState === 'complete' || (document.readyState !== 'loading' && !document.documentElement.doScroll)) {
    altumcodeprestart();
} else {
    document.addEventListener('DOMContentLoaded', () => {
        altumcodeprestart();
    });
}



