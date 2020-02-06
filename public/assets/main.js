// Damir Sijakovic 2019

// returns clean url
function getRequestUrl(){    
    let url = window.location.href;
    
    if (url.includes('?')){
        var res = url.split("?");
        return res[0];
    }
    else {
        let index = 'index.php';
        let urlName = url.split('/').pop();
        if (urlName == 'index.php'){
            url = url.substring(0, url.lastIndexOf("/"));
            index = '/index.php';
        } 
        return url + index;
    }
}

// scrollTo(document.body, 0, 600); //scroll to top
function scrollTo(element, to, duration) {
    if (duration <= 0) return;
    var difference = to - element.scrollTop;
    var perTick = difference / duration * 10;

    setTimeout(function() {
        element.scrollTop = element.scrollTop + perTick;
        if (element.scrollTop === to) return;
        scrollTo(element, to, duration - 10);
    }, 10);
}

// store/restore data as objects
function session(key, val){    
    if (val !== undefined) {     
        let jsonObj = JSON.stringify(val); 
        let encuri = encodeURIComponent(jsonObj);
        return localStorage.setItem(key, btoa(encuri));        
    }
    else {
        let encuri = atob(localStorage.getItem(key));  
        let json_obj = decodeURIComponent(encuri);
        return JSON.parse(json_obj); 
    }
}
