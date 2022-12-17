import '../styles/style.scss';

const close = document.getElementById('close');
const message = document.querySelector('.message-error, .message-success');

if (close) {
    function closeMessage() {
        close.style.display = 'none';
        message.remove();
    }
    // Closing manually and by timeout.
    close.addEventListener("click", () => closeMessage());
    setTimeout(() => closeMessage(), 6000);
}