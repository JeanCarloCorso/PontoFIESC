function updateTime() {
    const dateElement = document.querySelector('.date');
    const timeElement = document.querySelector('.time');

    const currentDate = new Date();

    const formattedDate = currentDate.toLocaleDateString('pt-BR');
    const formattedTime = currentDate.toLocaleTimeString('pt-BR');

    dateElement.textContent = formattedDate;
    timeElement.textContent = formattedTime;
}

setInterval(updateTime, 1000);
