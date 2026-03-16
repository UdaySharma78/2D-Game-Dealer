const gameArea = document.querySelector(".gameArea");
const scoreDisplay = document.querySelector(".score");

const startBtn = document.getElementById("startBtn");
const startScreen = document.querySelector(".startScreen");

const lanes = [60,190,320,450];

let player = {
speed:3,
score:0,
start:false
};

let keys = {
ArrowLeft:false,
ArrowRight:false
};

document.addEventListener("keydown",e=>{
if(e.key==="ArrowLeft") keys.ArrowLeft=true;
if(e.key==="ArrowRight") keys.ArrowRight=true;
});

document.addEventListener("keyup",e=>{
if(e.key==="ArrowLeft") keys.ArrowLeft=false;
if(e.key==="ArrowRight") keys.ArrowRight=false;
});

startBtn.addEventListener("click",()=>{
startScreen.style.display="none";
startGame();
});

function startGame(){

player.start=true;
player.score=0;

let car=document.createElement("div");
car.setAttribute("class","playerCar");
gameArea.appendChild(car);

player.x=lanes[1];
car.style.left=player.x+"px";

let cars=["game/konseg.png","game/dodge.png","game/porshe.png"];

for(let x=0;x<3;x++){

let enemy=document.createElement("div");

enemy.setAttribute("class","enemyCar");

enemy.style.backgroundImage=
"url(images/"+cars[Math.floor(Math.random()*cars.length)]+")";

enemy.style.top=(x*350)*-1+"px";

enemy.style.left=lanes[Math.floor(Math.random()*4)]+"px";

gameArea.appendChild(enemy);
}

window.requestAnimationFrame(gamePlay);
}

function gamePlay(){

if(!player.start) return;

let car=document.querySelector(".playerCar");

gameArea.style.backgroundPositionY=(player.score*0.5)+"px";

moveEnemy(car);
moveCoin(car);

if(keys.ArrowLeft){
let index=lanes.indexOf(player.x);
if(index>0) player.x=lanes[index-1];
keys.ArrowLeft=false;
}

if(keys.ArrowRight){
let index=lanes.indexOf(player.x);
if(index<lanes.length-1) player.x=lanes[index+1];
keys.ArrowRight=false;
}

car.style.left=player.x+"px";

player.score++;
scoreDisplay.innerHTML="Score: "+player.score;

if(player.score%400===0){
player.speed+=0.3;
}

if(Math.random()<0.02){
spawnCoin();
}

window.requestAnimationFrame(gamePlay);
}

function moveEnemy(car){

let enemy=document.querySelectorAll(".enemyCar");

enemy.forEach(item=>{

if(isCollide(car,item)){
endGame(car);
}

let top=item.offsetTop;

if(top>=800){
top=-200;
item.style.left=lanes[Math.floor(Math.random()*4)]+"px";
}

top+=player.speed;

item.style.top=top+"px";

});
}

function spawnCoin(){

let coin=document.createElement("div");

coin.classList.add("coin");

coin.style.left=lanes[Math.floor(Math.random()*4)]+"px";

coin.style.top="-100px";

gameArea.appendChild(coin);
}

function moveCoin(car){

let coins=document.querySelectorAll(".coin");

coins.forEach(coin=>{

let top=coin.offsetTop;

if(isCollide(car,coin)){
player.score+=100;
coin.remove();
}

if(top>800){
coin.remove();
}else{
top+=player.speed;
coin.style.top=top+"px";
}

});
}

function isCollide(a,b){

let aRect=a.getBoundingClientRect();
let bRect=b.getBoundingClientRect();

return !(
aRect.bottom<bRect.top||
aRect.top>bRect.bottom||
aRect.right<bRect.left||
aRect.left>bRect.right
);
}

function endGame(car){

player.start=false;

car.classList.add("crash");

document.getElementById("gameOverScreen").style.display="block";
}

function restartGame(){
location.reload();
}

gameArea.addEventListener("touchstart",e=>{

let touchX=e.touches[0].clientX;

if(touchX<window.innerWidth/2){
keys.ArrowLeft=true;
}else{
keys.ArrowRight=true;
}

});