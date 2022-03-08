function sort(param, value) {
    var url_string = window.location.href;
    var url = new URL(url_string);
    var url_param = url.searchParams.get(param);
    if(value == '') {
        url.searchParams.delete(param);
    } else {
        url.searchParams.set(param, value);
    }
    document.location.href = document.location.origin + document.location.pathname + "?" + url.searchParams.toString();
}