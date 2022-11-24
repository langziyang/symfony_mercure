import {Controller} from '@hotwired/stimulus';
import axios from "axios";

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static targets = ['message', 'messageArea', 'username']
    static values = {username: String, url: String}

    connect() {
        this.usernameValue = 'user' + parseInt((Math.random() * 1000))
        this.usernameTarget.innerText = this.usernameValue
        const eventSource = new EventSource(this.urlValue + "/.well-known/mercure?topic=chat")
        console.log(this.urlValue)
        eventSource.onmessage = resp => {
            const data = JSON.parse(resp.data)
            if (data.username === this.usernameValue) {
                this.messageAreaTarget.insertAdjacentHTML('afterbegin', '<div class="my-2 text-primary">Me: ' + data.message + '</div>')
            } else {
                this.messageAreaTarget.insertAdjacentHTML('afterbegin', '<div class="my-2 text-secondary">' + data.username + ": " + data.message + '</div>')
            }
        }
    }

    sendMessage(event) {
        const message = this.messageTarget.value
        if (message.length <= 0) {
            alert("Message is empty")
        } else {
            axios.post(this.urlValue + ':8000/chat', {message, username: this.usernameValue}).then(resp => {
                this.messageTarget.value = ''
            })
        }
    }

}
