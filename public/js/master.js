window.addEventListener('load', () => {
    const overlay = document.querySelector('.overlay')
    const deleteButtons = document.querySelectorAll('[data-action="master-delete"]')
    const cancelButton = document.querySelector('#cancelButton')
    const confirmForm = document.querySelector('#confirm')
    const confirmDelete = document.querySelector('.confirm-delete')
    const confirmDeleteText = confirmDelete.querySelector('.confirm-delete__text')

    const overlayOff = () => {
        overlay.classList.remove('active')
        document.body.classList.remove('ov-hid')
        confirmDelete.classList.remove('show')
    }

    overlay.addEventListener('click', overlayOff)
    cancelButton.addEventListener('click', overlayOff)

    const overlayOn = (event) => {
        overlay.classList.add('active')
        document.body.classList.add('ov-hid')
        const elem = event.target
        const id = elem.getAttribute('data-id')
        const name = elem.getAttribute('data-name')
        confirmForm.setAttribute('action', '/master/' + id)
        confirmDeleteText.innerHTML = 'Сейчас будет удалён мастер<br>' + name + '.<br>Вы уверены?'
        confirmDelete.classList.add('show')
    }

    deleteButtons.forEach(btn => {
        btn.addEventListener('click', (event) => overlayOn(event))
    })
})