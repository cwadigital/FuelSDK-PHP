$(document).ready(function() {
    function filterPath(string) {
        return string
            .replace(/^\//,'')
            .replace(/(index|default).[a-zA-Z]{3,4}$/,'')
            .replace(/\/$/,'');
    }

    const locationPath = filterPath(location.pathname);

    $('a[href*=#]').each(function() {
        const thisPath = filterPath(this.pathname) || locationPath;
        if (  locationPath == thisPath
            && (location.hostname == this.hostname || !this.hostname)
            && this.hash.replace(/#/,'') ) {
            const $target = $(this.hash), target = this.hash;
            if (target) {
                $(this).click(function(event) {
                    if (!$(this.hash).offset()) {
                        return;
                    }

                    event.preventDefault();
                    position = $(this.hash).offset().top;

                    $('html,body').animate({scrollTop: position}, 400, function() {
                        location.hash = target;
                    });
                });
            }
        }
    });
});
