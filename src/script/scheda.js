/*
Da fare: 

- rimuovere il candidato scelto dall'altro select


*/






function formCheck(ele) {

    checkNotSelected(); // controlla se un elemento non selezionato possiede attributi che lo rappresentano come un dato selezionato, se sì, li cancella

    attivaForm(ele.value);

    togliOpacita(ele.value);

    info();
    
    var card = document.getElementById(ele.value);

    card.classList.remove("border-gray-100");
    card.classList.add("border-blue-500");
    card.classList.remove("hover:border-blue-400");
}

function checkNotSelected() {  // La funzione cerca i div dei partiti che non sono spuntati, cancella la classe del bordo

    var radioButton = document.getElementsByName('sceltaPartito');

    for(i = 0; i < radioButton.length; i++) {
        if(!radioButton[i].checked) {
            var card = document.getElementById(radioButton[i].value);

            card.classList.remove("border-blue-500");
            card.classList.add("border-gray-100");
            card.classList.add("hover:border-blue-400");

            aggiungiOpacita(radioButton[i].value);
            disabilitaForm(radioButton[i].value);
        }
    }

}

function togliOpacita(codicePartito) {
    var select = document.getElementById('selects-'+codicePartito);

    select.classList.remove("opacity-30");
}

function aggiungiOpacita(codicePartito) {
    var select = document.getElementById('selects-'+codicePartito);

    select.classList.add("opacity-30");
}

function disabilitaForm(codicePartito) {
    var pref1 = document.getElementById('pref1-'+codicePartito);    // ottengo il select della prima preferenza
    var pref2 = document.getElementById('pref2-'+codicePartito);    // ottengo il select della seconda preferenza

    var bottone = document.getElementById('btn-'+codicePartito);

    pref1.classList.add("cursor-not-allowed");  // modifico il cursore quando l'utente fa hover sulla pref1
    pref1.disabled = true;     // disabilito il select

    pref2.classList.add("cursor-not-allowed");
    pref2.disabled = true;

    bottone.classList.add("cursor-not-allowed");
    bottone.disabled = true;
}

function attivaForm(codicePartito) {
    var pref1 = document.getElementById('pref1-'+codicePartito);    // ottengo il select della prima preferenza
    var pref2 = document.getElementById('pref2-'+codicePartito);    // ottengo il select della seconda preferenza

    var bottone = document.getElementById('btn-'+codicePartito);

    pref1.classList.remove("cursor-not-allowed");  // modifico il cursore quando l'utente fa hover sulla pref1
    pref1.disabled = false;     // disabilito il select

    pref2.classList.remove("cursor-not-allowed");
    pref2.disabled = false;

    bottone.classList.remove("cursor-not-allowed");
    bottone.disabled = false;

}

function info() {   // questa funzione modifica l'info box in cima alla pagina per dare indicazioni all'utente
    var info = document.getElementById('info');

    info.innerHTML = "Adesso puoi scegliere le preferenze premendo sulla casella 'Scegli la prima preferenza' e 'Scegli la seconda preferenza'. Se non vuoi esprimere le tue preferenze, puoi inviare anche solo la scelta del partito"
}






