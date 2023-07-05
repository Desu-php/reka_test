import dispatch from "./dispatch.js";

export default function () {

    const forms = document.querySelectorAll('[data-form]')

    let activeForm

    forms.forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault()

            activeForm = e.target

            clearErrors(activeForm)

            const button = activeForm.querySelector('button[type="submit"]')

            button.setAttribute('disabled', true)

            const formData = new FormData(this)

            await request(activeForm.getAttribute('action'), activeForm.getAttribute('method'), formData)

            button.removeAttribute('disabled')
        })
    })

    const request = async (url, method, formData) => {
        try {
            const {data} = await axios({
                method,
                url,
                data: formData
            });

            if (data.message) {
                alert(data.message)
            }

            if (!activeForm.dataset.update) {
                activeForm.reset()
            }

            dispatch(activeForm, 'success')
        } catch (e) {
            console.log('e', e)
            if (e.response) {
                if (e.response.status === 422) {
                    console.log('e.response.data', e.response.data)
                    errors(e.response.data.errors)
                }

                if (e.response.data.message) {
                    alert(e.response.data.message)
                }
            } else {
                alert('Что-то пошло не так повторите позже')
            }
        }
    }

    const errors = (errors) => {
        for (const key in errors) {
            const input = activeForm.querySelector(`[name=${key}]`)

            input.classList.add('is-invalid')

            input.insertAdjacentHTML('afterend', `<div class="invalid-feedback">${errors[key]}</div>`)
        }
    }

    const clearErrors = (form) => {
        form.querySelectorAll('.is-invalid').forEach(input => {
            input.classList.remove('is-invalid')

            input.parentElement.querySelector('.invalid-feedback').remove()
        })
    }
}
