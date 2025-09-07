</main>

<script>
    // Atualizar hora atual
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }
    
    // Atualizar a cada segundo
    setInterval(updateTime, 1000);
    updateTime(); // Executar imediatamente
</script>

</body>
</html>