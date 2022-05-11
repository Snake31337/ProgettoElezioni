/*
Da fare: 

- colorare il bordo del partito scelto


*/






function coloraBordo(ele) {
    console.log(ele);

    var card = document.getElementById(ele.value);

    console.log(card);

    card.classList.add("border-2");
    card.classList.add("border-blue-700");
}



var radioButton = document.getElementsByName('sceltaPartito');

for(i = 0; i < radioButton.length; i++) {
    if(radioButton[i].checked)
    console.log(radioButton[i]);
}
