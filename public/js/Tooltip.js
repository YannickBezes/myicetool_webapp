class Tooltip {
    constructor() {
        this.$tooltip = new google.maps.InfoWindow({
            content: this.createContent()
        })
    }

    createContent() {
        var div = document.createElement('div')
        div.className = 'tlp'

        var span = document.createElement('span')
        span.hidden = true
        span.id = 'polygone'
        div.appendChild(span)

        // Button valided
        var spanValid = document.createElement('span')
        spanValid.className = 'material-icons'
        spanValid.textContent = 'check'
        var linkValid = document.createElement('a')
        linkValid.onclick = function() {
            document.getElementById('polygone').value.valid()
        }
        linkValid.appendChild(spanValid)
        div.appendChild(linkValid)

        // Button edit
        var spanEdit = document.createElement('span')
        spanEdit.className = 'material-icons'
        spanEdit.textContent = 'mode_edit'
        var linkEdit = document.createElement('a')
        linkEdit.onclick = function() {
            document.getElementById('polygone').value.edit()
        }
        linkEdit.appendChild(spanEdit)
        div.appendChild(linkEdit)

        // Button delete
        var spanDelete = document.createElement('span')
        spanDelete.className = 'material-icons'
        spanDelete.textContent = 'delete'
        var linkDelete = document.createElement('a')
        linkDelete.onclick = function() {
            document.getElementById('polygone').value.delete()
        }
        linkDelete.appendChild(spanDelete)
        div.appendChild(linkDelete)

        return div
    }
}