const weatherForm = document.querySelector(".weatherForm");
const cityInput = document.querySelector(".cityInput");
const card = document.querySelector(".card");

// Fetch the API key securely
const apiKey = "YOUR_API_KEY"; // Replace with your API key or leave as a placeholder

weatherForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    const city = cityInput.value.trim(); // Trim extra spaces

    if (city) {
        try {
            const weatherData = await getWeatherData(city);
            displayWeatherInfo(weatherData);
        } catch (error) {
            console.error(error);
            displayError("Unable to fetch weather. Check the city name or try again.");
        }
    } else {
        displayError("Please enter a valid city.");
    }
});

async function getWeatherData(city) {
    const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${encodeURIComponent(
        city
    )}&appid=${apiKey}`;
    const response = await fetch(apiUrl);

    if (!response.ok) {
        if (response.status === 404) {
            throw new Error("City not found");
        }
        if (response.status === 401) {
            throw new Error("Invalid API Key");
        }
        throw new Error("Could not fetch weather data");
    }

    return await response.json();
}

function displayWeatherInfo(data) {
    const {
        name: city,
        main: { temp, humidity },
        weather: [{ description, id }],
    } = data;

    card.textContent = "";
    card.style.display = "flex";

    const cityDisplay = document.createElement("h1");
    const tempDisplay = document.createElement("p");
    const humidityDisplay = document.createElement("p");
    const descDisplay = document.createElement("p");
    const weatherEmoji = document.createElement("p");

    cityDisplay.textContent = city;
    tempDisplay.textContent = `${((temp - 273.15) * (9 / 5) + 32).toFixed(1)}°F`;
    humidityDisplay.textContent = `Humidity: ${humidity}%`;
    descDisplay.textContent = description;
    weatherEmoji.textContent = getWeatherEmoji(id);

    card.appendChild(cityDisplay);
    card.appendChild(tempDisplay);
    card.appendChild(humidityDisplay);
    card.appendChild(descDisplay);
    card.appendChild(weatherEmoji);
}

function getWeatherEmoji(weatherId) {
    switch (true) {
        case weatherId >= 200 && weatherId < 300:
            return "⛈️ Thunderstorm";
        case weatherId >= 300 && weatherId < 400:
            return "🌦️ Drizzle";
        case weatherId >= 500 && weatherId < 600:
            return "🌧️ Rain";
        case weatherId >= 600 && weatherId < 700:
            return "❄️ Snow";
        case weatherId >= 700 && weatherId < 800:
            return "🌫️ Mist";
        case weatherId === 800:
            return "☀️ Clear Sky";
        case weatherId >= 801 && weatherId < 810:
            return "☁️ Clouds";
        default:
            return "🌍";
    }
}

function displayError(message) {
    card.textContent = "";
    card.style.display = "flex";

    const errorDisplay = document.createElement("p");
    errorDisplay.textContent = message;
    errorDisplay.classList.add("errorDisplay");

    card.appendChild(errorDisplay);
}
