'use strict';

const overlay = document.querySelector("[data-overlay]");
const formOpen = document.querySelector("[data-add-user]");
const closeBtn = document.querySelector("[data-close-btn]");
const closeBtn2 = document.querySelector("[data-close-btn2]");
const form = document.querySelector("[data-form-add]");

const add_btn = [overlay, formOpen, closeBtn, closeBtn2];


for (let i = 0; i < add_btn.length; i++) {
    add_btn[i].addEventListener("click", function () {
      overlay.classList.toggle("active");
      form.classList.toggle("active");
    });
  }


// Checkbox Check All

const checkAll = document.querySelector("#checkAll");
const checkboxes = document.querySelectorAll(".form-check-input")
var middle = true;

if(checkboxes.length){
    checkAll.addEventListener("click", function(){
      checkboxes.forEach(el => {
        el.checked = middle;
      });
    
      checkAll.innerHTML = (middle) ? "Uncheck All" : "Check All";
      middle = !middle;
    
    })
}
