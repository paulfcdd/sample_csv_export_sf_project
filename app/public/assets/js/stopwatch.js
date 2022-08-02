$(function () {
    $('.stopwatch').each(function () {
        let element = $(this);
        let running = element.data('autostart');
        let hoursElement = element.find('.hours');
        let minutesElement = element.find('.minutes');
        let secondsElement = element.find('.seconds');
        let millisecondsElement = element.find('.milliseconds');
        let toggleElement = element.find('.toggle');
        let resetElement = element.find('.reset');
        let pauseText = toggleElement.data('pausetext');
        let resumeText = toggleElement.data('resumetext');
        let startText = toggleElement.text();
        let hours, minutes, seconds, milliseconds, timer;

        function prependZero(time, length) {
            time = '' + (time | 0);
            while (time.length < length) time = '0' + time;
            return time;
        }

        function setStopwatch(hours, minutes, seconds, milliseconds) {
            hoursElement.text(prependZero(hours, 2));
            minutesElement.text(prependZero(minutes, 2));
            secondsElement.text(prependZero(seconds, 2));
            millisecondsElement.text(prependZero(milliseconds, 3));
        }

        function runTimer() {
            let startTime = Date.now();
            let prevHours = hours;
            let prevMinutes = minutes;
            let prevSeconds = seconds;
            let prevMilliseconds = milliseconds;

            timer = setInterval(function () {
                let timeElapsed = Date.now() - startTime;

                hours = (timeElapsed / 3600000) + prevHours;
                minutes = ((timeElapsed / 60000) + prevMinutes) % 60;
                seconds = ((timeElapsed / 1000) + prevSeconds) % 60;
                milliseconds = (timeElapsed + prevMilliseconds) % 1000;

                setStopwatch(hours, minutes, seconds, milliseconds);
            }, 25);
        }

        function run() {
            running = true;
            runTimer();
            toggleElement.text(pauseText);
        }

        function pause() {
            running = false;
            clearTimeout(timer);
            toggleElement.text(resumeText);
        }

        function reset() {
            running = false;
            pause();
            hours = minutes = seconds = milliseconds = 0;
            setStopwatch(hours, minutes, seconds, milliseconds);
            toggleElement.text(startText);
        }

        toggleElement.on('click', function () {
            (running) ? pause() : run();
        });

        resetElement.on('click', function () {
            reset();
        });

        reset();
        if(running) run();
    });

});