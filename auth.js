import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
import { getDatabase, set, ref, update, push, onValue } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-database.js";
import { getAuth, createUserWithEmailAndPassword, signInWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyBMYEeVOS2KlDWq4t8kO6CjmNDhEEXu3T0",
    authDomain: "test-f9d55.firebaseapp.com",
    databaseURL: "https://test-f9d55-default-rtdb.firebaseio.com",
    projectId: "test-f9d55",
    storageBucket: "test-f9d55.appspot.com",
    messagingSenderId: "1055040824626",
    appId: "1:1055040824626:web:1fe0ef6dfe78837ea0a32b"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const database = getDatabase(app);
const auth = getAuth();

// Создаем глобальную ссылку на функцию registerUser()
window.registerUser = registerUser;
window.showTab = showTab;
window.loginUser = loginUser;

function showTab(tabName) {
    const registerTab = document.getElementById('registerTab');
    const loginTab = document.getElementById('loginTab');
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');

    if (tabName === 'register') {
        registerTab.classList.add('active');
        loginTab.classList.remove('active');
        registerForm.style.display = 'block';
        loginForm.style.display = 'none';
    } else {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
    }
}

function registerUser() {
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const username = document.getElementById('registerUsername').value;

    createUserWithEmailAndPassword(auth, email, password)
        .then((userCredential) => {
            const user = userCredential.user;
            set(ref(database, 'users/' + user.uid), {
                username: username,
                email: email,
                // Другие поля пользователя, которые вы хотите сохранить
            })

            alert(`Successfully registered user with email: ${email}`);
            document.getElementById('registerForm').reset();
        })
        .catch((error) => {
            alert('Error registering user:', error);
        });
}

function loginUser() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    signInWithEmailAndPassword(auth, email, password)
        .then((userCredential) => {
            // Signed in 
            const user = userCredential.user;

            const dt = new Date();
            update(ref(database, 'users/' + user.uid), {
                last_login: dt,
            })

            //alert(`Successfully registered user with email: ${email}`);


            window.location.href = 'index.html'; // Переход на страницу "Создания события"
            auth.signInWithEmailAndPassword(email, password)
            .then((userCredential) => {
                // Получаем информацию о пользователе после успешного входа
                const user = userCredential.user;
                const username = user.displayName; // Получаем имя пользователя
    
                // Обновляем элемент в HTML для отображения имени пользователя
                const welcomeMessage = document.getElementById('welcomeMessage');
                welcomeMessage.textContent = `Welcome, ${username}!`; // Обновляем приветствие
    
                // // Скрываем форму входа
                // document.getElementById('loginForm').style.display = 'none';
            })

        })
        .catch((error) => {
            alert('Неверный логин или пароль')

        });

    console.log(`Logging in user with email: ${email}`);
}

// Show the register tab by default
showTab('register');


