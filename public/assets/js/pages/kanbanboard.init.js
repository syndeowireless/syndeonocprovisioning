
/*
Template Name: Lexa - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Kanbanboard Init Js File
*/

dragula([
    document.getElementById("todo-task"), 
    document.getElementById("inprogress-task"),
    document.getElementById("hold-task"),
    document.getElementById("completed-task")
]);