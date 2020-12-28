/* Helpers */
let is_do_not_track = () => {
    if(window.doNotTrack || navigator.doNotTrack || navigator.msDoNotTrack) {

        return window.doNotTrack == "1" || navigator.doNotTrack == "yes" || navigator.doNotTrack == "1" || navigator.msDoNotTrack == "1";

    } else {
        return false;
    }
};

let pixel_verify = () => {
    let params = (new URL(document.location)).searchParams;

    /* Disable tracking and set a cookie for the future */
    let pixel_verify_query = params.get('pixel_verify');

    if(pixel_verify_query !== null) {

        /* Check against the pixel key if needed */
        if(pixel_verify_query != '') {

            if(pixel_key == pixel_verify_query) {
                alert(pixel_key_verify_message);
            }

        } else {

            /* If its a simpler check, just echo that it has been installed */
            alert(pixel_key_verify_message);
        }

    }
};

let get_current_url_domain_no_www = () => {
    let url = window.location.href.replace(window.location.protocol + '//', '');

    /* Remove www. from the host */
    if(url.startsWith('www.')) {
        url = url.replace('www.', '');
    }

    return url;
}
