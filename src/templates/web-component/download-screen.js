class LWCDownloadScreen extends HTMLElement {
    constructor() {
        super();
        this.shadow = this.attachShadow({mode: 'open'});
    }

    connectedCallback() {
        this.render();
    }

    render() {
        this.shadow.innerHTML = `
            <style>
                .circle {
                    width: 100px;
                    height: 100px;
                    background-color: red;
                    border-radius: 50%;
                }
            </style>
            <div>
                <h2>Cart Icon</h2>
                <div class="circle" id='circle'></div>
            </div> 
        `;
    }
}

window.customElements.define('lwc-download-screen', LWCDownloadScreen)

