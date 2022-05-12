/*
Da fare: 

- colorare il bordo del partito scelto


*/






function coloraBordo(ele) {
    console.log(ele);

    cancellaBordo();
    
    var card = document.getElementById(ele.value);

    togliOpacita(ele.value);

    console.log(card);

    card.classList.remove("border-gray-100");
    card.classList.add("border-blue-500");
    card.classList.remove("hover:border-blue-400");
}

function cancellaBordo() {  // La funzione cerca i div dei partiti che non sono spuntati, cancella la classe del bordo

    var radioButton = document.getElementsByName('sceltaPartito');

    for(i = 0; i < radioButton.length; i++) {
        if(!radioButton[i].checked) {
            var card = document.getElementById(radioButton[i].value);

            card.classList.remove("border-blue-500");
            card.classList.add("border-gray-100");
            card.classList.add("hover:border-blue-400");

            aggiungiOpacita(radioButton[i].value);
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

function disabilitaSelect(codicePartito) {
    var pref1 = document.getElementById('pref1-'+codicePartito)
}






