let navbar = document.querySelector('.header .navbar');
let accountBox = document.querySelector('.header .accountBox');

document.querySelector('#menuBtn').onclick = () =>{
    navbar.classList.toggle('active');
    accountBox.classList.remove('active');
}

document.querySelector('#userBtn').onclick = () =>{
    accountBox.classList.toggle('active');
    navbar.classList.remove('active');
}

window.onscroll = () =>{
    navbar.classList.remove('active');
    accountBox.classList.remove('active');
}

document.querySelector('#close-update').onclick = () =>{
    document.querySelector('.editGamesForm').style.display = 'none';
    window.location.href = 'adminGames.php';
}