
// accordéons

var acc = document.getElementsByClassName("accordion");
    var i;

    for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var panel = this.nextElementSibling;
        if (panel.style.maxHeight) {
        panel.style.maxHeight = null;
        } else {
        panel.style.maxHeight = panel.scrollHeight + "px";
        } 
    });
    }
    // fin accordéons

    //btn menu burger and no-scroll
    const menuBtn  = document.querySelector('.menu-btn');
    const body  = document.querySelector('.body');
    let menuOpen = false;
    document.querySelector('#NavToggle').addEventListener('change', ()=> {
        if(!menuOpen){
            menuBtn.classList.add('open');
            body.classList.add('no-scroll');
            menuOpen = true;
        } else {
            menuBtn.classList.remove('open');
            body.classList.remove('no-scroll');
            menuOpen = false;
        }
    });
    // fin btn menu burger

    // bouton card

    var btnCard = document.getElementsByClassName("btnCard");
    
    var i;

    for (i = 0; i < btnCard.length; i++) {
    btnCard[i].addEventListener("click", function() {
        this.classList.toggle("activeCard");
        var panelCard = this.nextElementSibling;
        var card = this.parentElement;
        if (panelCard.style.maxHeight) {
        panelCard.style.maxHeight = null;
        card.style.maxHeight = null;
        } else {
        panelCard.style.maxHeight = panelCard.scrollHeight + "px";
        card.style.maxHeight = card.scrollHeight + panelCard.scrollHeight + "px";
        }
    });
    }
    // fin bouton card