        // Import the functions you need from the SDKs you need
        import page from 'https://unpkg.com/page/page.js';
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
        import { getDatabase, ref, push, onValue } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-database.js";

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

        page('/', showRegistrationScreen);
        page('/create-event', showCreateEventScreen);

        page();

function showRegistrationScreen() {
    const appDiv = document.getElementById('app');
    appDiv.innerHTML = '';
    fetch('auth.html')
        .then(response => response.text())
        .then(data => {
            appDiv.innerHTML = data;
            showTab('register');
        });
}

function showCreateEventScreen() {
    const appDiv = document.getElementById('app');
    appDiv.innerHTML = '';
    fetch('create-event.html')
        .then(response => response.text())
        .then(data => {
            appDiv.innerHTML = data;
            // Your logic for displaying events and creating events goes here
            // Make sure to add event listeners for the create event button
        });
}

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

  // Implement user registration logic (use Firebase authentication or other method)
  console.log(`Registering user with email: ${email}`);
}

function loginUser() {
  const email = document.getElementById('loginEmail').value;
  const password = document.getElementById('loginPassword').value;

  // Implement user login logic (use Firebase authentication or other method)
  console.log(`Logging in user with email: ${email}`);
}

// Show the register tab by default
showTab('register');



        // Объявление переменной eventsRef в глобальной области видимости
        let eventsRef;

        // Функция для создания события
            function createEvent() {
            const selectedSport = document.getElementById('sportSelect').value;
            const eventDescription = document.getElementById('eventDescription').value;
            const eventDate = document.getElementById('eventDate').value;
            const eventLocation = document.getElementById('eventLocation').value;
            const availableSpots = document.getElementById('availableSpots').value;

    // Добавление события в базу данных
        push(eventsRef, {
            sport: selectedSport,
            eventDescription: eventDescription,
            eventDate: eventDate,
            eventLocation: eventLocation,
            availableSpots: availableSpots
    });

            // Очистка полей формы
            document.getElementById('eventForm').reset();
        }

        // Слушатель событий для обновления списка событий
        eventsRef = ref(database, 'events');
        onValue(eventsRef, (snapshot) => {
            const eventList = document.getElementById('eventList');
            eventList.innerHTML = '';

            snapshot.forEach((childSnapshot) => {
                const eventData = childSnapshot.val();
                const listItem = document.createElement('li');
                listItem.textContent = `${eventData.sport} - ${eventData.eventDate} - ${eventData.eventLocation}`;
                eventList.appendChild(listItem);
            });
        });

        // Добавляем слушатель события click для кнопки "Create Event"
        document.getElementById('createEventButton').addEventListener('click', createEvent);