$(document).ready(function(){

var fullW = $("#window").width();
var reserveW = $("#navigation-menu").width();
var contentW = fullW - reserveW -50;
$("#content").width(contentW);


});