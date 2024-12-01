<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Widget</title>
    <style>
        .weather-icon {
            font-size: 4rem;
            cursor: pointer;
            position: fixed;
            bottom: 50px;
            right: 15px;
            transition: transform 0.3s ease;
            z-index: 100;
        }

        .weather-icon:hover {
            transform: scale(1.1);
        }

        .weather-icon img{
            height: 60px;
        }

        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: none;
            width: 400px;
            height: 500px;
        }

        .popup.active {
            display: block;
        }

        /* Weather system styling */
        .weather-container {
            font-family: 'Poppins', Arial, sans-serif;
            background: linear-gradient(510deg, hsl(180, 2%, 12%), hsl(180, 1%, 41%), hsl(204, 91%, 64%), hsl(204, 91%, 64%));
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            overflow: hidden;
            position: relative;
            border-radius: 15px;
            width: 100%;
        }

        .sun {
            position: absolute;
            top: 10%;
            right: 10%;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, hsl(50, 100%, 60%), hsl(50, 100%, 50%));
            border-radius: 50%;
            box-shadow: 0px 0px 30px rgba(255, 200, 0, 0.7);
            animation: slowSpin 20s linear infinite;
        }

        .clouds {
            position: absolute;
            top: 20%;
            left: 0;
            width: 100%;
            height: 200px;
            pointer-events: none;
            overflow: hidden;
        }

        .cloud {
            position: absolute;
            background: linear-gradient(to bottom, hsl(0, 0%, 100%), hsl(0, 0%, 90%));
            border-radius: 50%;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            animation: floatClouds linear infinite;
        }

        .cloud:nth-child(1) {
            width: 80px;
            height: 60px;
            left: -100px;
            animation-duration: 30s;
        }

        .cloud:nth-child(2) {
            width: 140px;
            height: 100px;
            left: -200px;
            animation-duration: 40s;
        }

        @keyframes slowSpin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes floatClouds {
            from {
                transform: translateX(-200px);
            }
            to {
                transform: translateX(100vw);
            }
        }

        .rain {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .raindrop {
            position: absolute;
            bottom: 100%;
            width: 2px;
            height: 50px;
            background: hsla(211, 36%, 85%, 0.6);
            animation: fall linear infinite;
            opacity: 0.5;
        }

        @keyframes fall {
            to {
                transform: translateY(100vh);
            }
        }

        .weatherForm {
            margin: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }

        .cityInput {
            padding: 10px;
            font-size: 1.5rem;
            font-weight: 600;
            border: 2px solid hsla(0, 0%, 20%, 0.2);
            border-radius: 50px;
            margin: 10px;
            width: 300px;
            text-align: center;
        }

        .weatherForm button[type="submit"] {
            padding: 15px 40px;
            font-weight: 700;
            font-size: 1rem;
            background-color: hsl(122, 39%, 50%);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
        }

        .weather-card {
            background: linear-gradient(145deg, hsl(210, 100%, 75%), hsl(40, 100%, 75%));
            padding: 15px;
            border-radius: 20px;
            display: none;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }
    </style>
</head>

<body>

    <div class="weather-icon" id="weatherIcon"><img src="../images/Untitled-1.png" alt=""></div>

    <div class="popup" id="popup">
        <div class="weather-container">
            <div class="sun"></div>
            <div class="clouds">
                <div class="cloud"></div>
                <div class="cloud"></div>
            </div>
            <div class="rain"></div>

            <form class="weatherForm">
                <input type="text" class="cityInput" placeholder="Enter city">
                <button type="submit">Get Weather</button>
            </form>

            <div class="weather-card" id="weatherCard"></div>
        </div>
    </div>

    <script>
        const weatherIcon = document.getElementById('weatherIcon');
        const popup = document.getElementById('popup');
        const weatherForm = document.querySelector('.weatherForm');
        const cityInput = document.querySelector('.cityInput');
        const card = document.getElementById('weatherCard');
        const apiKey = '7e86889cca24828afb3339b4ffcfbb8a';

        weatherIcon.addEventListener('click', () => {
            popup.classList.toggle('active');
        });

        weatherForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            const city = cityInput.value;
            if (city) {
                try {
                    const weatherData = await getWeatherData(city);
                    displayWeatherInfo(weatherData);
                } catch (error) {
                    console.error(error);
                    displayError(error);
                }
            } else {
                displayError('Please enter a city');
            }
        });

        async function getWeatherData(city) {
            const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}`;
            const response = await fetch(apiUrl);
            if (!response.ok) throw new Error('Could not fetch weather data');
            return await response.json();
        }

        function displayWeatherInfo(data) {
            const { name: city, main: { temp, humidity }, weather: [{ description, id }] } = data;
            card.innerHTML = `
                <h1>${city}</h1>
                <p>${((temp - 273.15) * (9 / 5) + 32).toFixed(1)}°F</p>
                <p>Humidity: ${humidity}%</p>
                <p>${description}</p>
                <p>${getWeatherEmoji(id)}</p>
            `;
            card.style.display = 'flex';
        }

        function getWeatherEmoji(weatherId) {
            if (weatherId >= 200 && weatherId < 300) return '⛈️';
            if (weatherId >= 300 && weatherId < 400) return '🌧️';
            if (weatherId >= 500 && weatherId < 600) return '🌧️';
            if (weatherId >= 600 && weatherId < 700) return '❄️';
            if (weatherId >= 700 && weatherId < 800) return '🌫️';
            if (weatherId === 800) return '☀️';
            if (weatherId >= 801 && weatherId < 900) return '☁️';
            return '🌈';
        }

        function displayError(message) {
            card.innerHTML = `<h1>Error</h1><p>${message}</p>`;
            card.style.display = 'flex';
        }
    </script>

</body>

</html>
