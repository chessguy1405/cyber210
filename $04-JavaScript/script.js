"use strict";

function buttonPopup() {
    alert("Hello, Button!");
}

function isNumber(sample) {
    let t;
    if (typeof(sample) == "number") {
        t = "Number";
    }
    else {
        t = "Something Else";
    }

    console.log(t);
}

function reportTrueFalse(x) {
    if (x) {
        console.log(x + " (" + typeof(x) + ") true");
    }
    else {
        console.log(x + " (" + typeof(x) + ") false");
    }
}

function reportEqual(a,b) {
    let equal = a == b;
    console.log(equal);
}

function reportExactlyEqual(a,b) {
    let equal = a === b;
    console.log(equal);
}

isNumber(5);
isNumber("comb");

reportTrueFalse("");
reportTrueFalse("Hello");
reportTrueFalse(32);
reportTrueFalse(false);
reportTrueFalse(true);
reportTrueFalse("false");

reportEqual(1, "1");
reportExactlyEqual(1, "1");

let myObject = {
    name: "Lancelot",
    favoriteColor: "Blue",

    reportColor: function() {
        console.log(this.favoriteColor);
    }
};

myObject.reportColor();

myObject.slogan = "The Brave";
console.log(myObject);

myObject.changeColor = function() {
    if (this.favoriteColor == "Blue") {
        this.favoriteColor = "Green";
    }
    else {
        this.favoriteColor = "Blue";
    }
}

myObject.reportColor();
myObject.changeColor();
myObject.reportColor();
myObject.changeColor();
myObject.reportColor();

function whenWindowLoads() {
    console.log("window.load event fired!");
}

window.addEventListener('load', whenWindowLoads);