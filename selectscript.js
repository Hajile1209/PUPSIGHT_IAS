// Rotating background images
        const images = [
            'images/main_1.jpg',
            'images/main_2.jpg',
            'images/main_4.jpg',
            'images/cite-campus.jpg',
            'images/cite_1.jpg',
            'images/cite_2.jpg',
            'images/cite_4.jpg',   
        ];
        let idx = 1;
        function changeBg() {
            document.getElementById('bg').style.backgroundImage = 'url(' + images[idx] + ')';
            idx = (idx + 1) % images.length;
        }
        window.onload = function() {
            changeBg();
            setInterval(changeBg, 4000); // 4 seconds per bg
        };