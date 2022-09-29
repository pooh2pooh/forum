const toastTrigger = document.getElementById('copyToClipboardButton')
const toastLiveExample = document.getElementById('copyToClipboard')
if (toastTrigger) {
  toastTrigger.addEventListener('click', () => {
    const toast = new bootstrap.Toast(toastLiveExample)
    try {
      navigator.clipboard.writeText(location.href);
      console.log('URL страницы скопирован в буфер обмена');
    } catch (err) {
      console.error('Не удалось скопировать: ', err);
    }

    toast.show()
  })
}