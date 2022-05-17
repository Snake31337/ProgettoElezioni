/*
Da fare: 

- fare il reset delle preferenze per risolvere il problema dell'inserimento di una preferenza a caso quando si cambia la scelta partito


*/



setInterval(setData,1000);


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

function checkNotSelected() {  // La funzione cerca i div dei partiti che non sono spuntati

    var radioButton = document.getElementsByName('sceltaPartito');

    for (i = 0; i < radioButton.length; i++) {
        if (!radioButton[i].checked) {
            var card = document.getElementById(radioButton[i].value);

            card.classList.remove("border-blue-500");   // Se il partito non è spuntato cancella il bordo
            card.classList.add("border-gray-100");      // Viene rimesso il bordo di default
            card.classList.add("hover:border-blue-400"); // Viene rimesso l'hover di default

            aggiungiOpacita(radioButton[i].value);
            disabilitaForm(radioButton[i].value);

            resetPreferenze(radioButton[i].value);
        }
    }

}

function togliOpacita(codicePartito) {  // quando un partito viene selezionato si toglie l'opacità dal form per mostrarlo
    var select = document.getElementById('selects-' + codicePartito);

    select.classList.remove("opacity-30");
}

function aggiungiOpacita(codicePartito) {
    var select = document.getElementById('selects-' + codicePartito);   // quando un partito non è selezionato è meno visibile

    select.classList.add("opacity-30");
}

function disabilitaForm(codicePartito) {
    var pref1 = document.getElementById('pref1-' + codicePartito);    // ottengo il select della prima preferenza
    var pref2 = document.getElementById('pref2-' + codicePartito);    // ottengo il select della seconda preferenza

    var bottone = document.getElementById('btn-' + codicePartito);

    pref1.classList.add("cursor-not-allowed");  // modifico il cursore quando l'utente fa hover sulla pref1
    pref1.disabled = true;     // disabilito il select

    pref2.classList.add("cursor-not-allowed");
    pref2.disabled = true;

    bottone.classList.add("cursor-not-allowed");
    bottone.disabled = true;
}

function attivaForm(codicePartito) {
    var pref1 = document.getElementById('pref1-' + codicePartito);    // ottengo il select della prima preferenza
    var pref2 = document.getElementById('pref2-' + codicePartito);    // ottengo il select della seconda preferenza

    var bottone = document.getElementById('btn-' + codicePartito);

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


function setData() {
    var spanOra = document.getElementById("oraEsatta");

    const d = new Date();
    let h = addZero(d.getHours());
    let m = addZero(d.getMinutes());
    let s = addZero(d.getSeconds());
    let time = h + ":" + m + ":" + s;

    spanOra.innerHTML = time;
}

function addZero(i) {
    if (i < 10) {i = "0" + i}
    return i;
  }


/* function checkPreferenze(ele, codicePartito) {
    var select1 = document.getElementById('pref1-' + codicePartito);    // ottengo il select della prima preferenza
    var select2 = document.getElementById('pref2-' + codicePartito);    // ottengo il select della seconda preferenza

    if (ele.id == 'pref1-' + codicePartito) {
        var select = document.getElementById('pref2-' + codicePartito);
    } else {
        var select = document.getElementById('pref1' + codicePartito);
    }

    let pref1 = select1.value;  // prendo il codice del candidato della prima preferenza

    select2.childNodes.forEach(element => { // controllo se nel secondo select esiste una opzione per quel candidato; se sì la cancello
        if (element.value == pref1 && element.value !== "") {
            element.remove();
        }
    });
} */


var pref1 = "";
var pref2 = ""; 
function checkPreferenze(ele, codicePartito) {  // ele è l'elemento da cui è stato inviata la richiesta

    let numSelect = 0;
    var select;

    if (ele.id == 'pref1-' + codicePartito) {
        console.log("Richiesta inviata dal primo select");
        var select = document.getElementById('pref2-' + codicePartito); // se la richiesta è stata inviata dalla prima preferenza allora select = seconda preferenza
        numSelect = 2; // Assegno un valore 1 per capire quale select sto modificando
        
        if(pref2 != "") {
            //console.log("IF PREF2");
            select.add(pref2);
            pref2 = "";
        }
    } else {
        console.log("Richiesta inviata dal secondo select");
        var select = document.getElementById('pref1-' + codicePartito);  // se la richiesta è stata inviata dalla seconda preferenza allora select = prima preferenza
        numSelect = 1;

        if(pref1 != "") {
            //console.log("IF PREF2");
            select.add(pref1);
            pref1 = "";
        }
    }

    let preferenza = ele.value;  // valore della preferenza inserita dall'input (ele) = codiceCandidato

    select.childNodes.forEach(element => { // controllo se nel select esiste una opzione per quel candidato; se sì la cancello
        if (element.value == preferenza && element.value !== "") {
            if(numSelect == 2) {
                pref2 = element;
                console.log("Elemento cancellato dal secondo select: ");
                console.log(pref2);
            } else if (numSelect == 1){
                pref1 = element;
                console.log("Elemento cancellato dal primo select: ");
                console.log(pref1);
            }
            element.remove();
        }
    });
}

function resetPreferenze(codicePartito) {
    let select1 = document.getElementById("pref1-" + codicePartito);
    let select2 = document.getElementById("pref2-" + codicePartito);

    select1.value = "";
    select2.value = "";

    pref1 = "";
    pref2 = "";
}

