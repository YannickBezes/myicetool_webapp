let edit = false

Marker.prototype.valid = function() {
    edit = false
    tlp.$tooltip.close()
    this.$marker.setOptions({
        editable: false,
        draggable: false
    })
    this.update()
}

Marker.prototype.edit = function() {
    edit = true
    this.$marker.setOptions({
        editable: true,
        draggable: true
    })
}

Marker.prototype.delete = function() {
    edit = false
    tlp.$tooltip.close()
    this.remove()
    $app.data().show = false
    fetch(`api/cascades/delete`, {
        method: 'post',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.cascade.id)
    }).catch((e) => console.error(e))
}

Zone.prototype.valid = function() {
    edit = false
    tlp.$tooltip.close()
    this.$rectangle.setOptions({
        editable: false,
        draggable: false
    })
    this.update()
}

Zone.prototype.edit = function() {
    edit = true
    this.$rectangle.setOptions({
        editable: true,
        draggable: true
    })
}

Zone.prototype.delete = function() {
    edit = false
    tlp.$tooltip.close()
    this.remove()
    $app.data().showZone = false
    fetch(`api/zones/delete`, {
        method: 'post',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.zone.id)
    }).catch((e) => console.error(e))
}

Map.prototype.addEvent = function() {
    this.$maps.addListener('bounds_changed', throttle(function() {
        if (!edit) {
            getFile(`api/zones`, function(data) {
                data.forEach(zone => {
                    Zone.check(zone)
                })
            })

            getFile(`api/cascades`, function(data) {
                data.forEach(cascade => {
                    Cascade.check(cascade)
                })
            })
        }
    }, 500))
}

map = new Map()
let tlp = new Tooltip()
let drawing = new Drawing()

Cascade.prototype.addEvent = function() {
    this.$marker.addListener('click', () => {
        map.$maps.panTo(this.position)

        tlp.$tooltip.open(map.$maps, this.$marker)
        tlp.$tooltip.content.children[0].value = this

        if (this.new == false) {
            getFile(`api/cascades/${this.cascade.id}/details`, (cascade) => {
                for (const key in cascade) {
                    let el = cascade[key]
                    if (el === null) {
                        if (key === 'niveau' || key === 'orientation' || key === 'structure' || key === 'type_fin_vie' || key === 'type_glace') {
                            $cascade.data().cascade[key] = { id: 0, libelle: 'selectionnez une valeur' }
                        } else {
                            $cascade.data().cascade[key] = 'entrez une valeur'
                        }
                    } else if (Array.isArray(el) && el.length < 1) {
                        if (key === 'commentaires' || key === 'images') {
                            $cascade.data().cascade[key] = []
                        } else {
                            $cascade.data().cascade[key] = [{ id: 0, libelle: 'selectionnez une valeur' }]
                        }
                    } else {
                        $cascade.data().cascade[key] = el
                    }
                }
                // $cascade.data().cascade = cascade
            })
        } else {
            $cascade.data().cascade.nom = 'entrez une valeur'
            $cascade.data().cascade.nombre_voies = 'entrez une valeur'
            $cascade.data().cascade.altitude_minimum = 'entrez une valeur'
            $cascade.data().cascade.hauteur = 'entrez une valeur'
            $cascade.data().cascade.niveau_engagement = 'entrez une valeur'
            $cascade.data().cascade.commentaires = []
            $cascade.data().cascade.constituants = [{ id:0, libelle: 'selectionnez une valeur' }]
            $cascade.data().cascade.images = [{ id:0, libelle: '', date: '', url: '' }]
            $cascade.data().cascade.niveau = { id: 0, libelle: 'selectionnez une valeur' }
            $cascade.data().cascade.orientation = { id: 0, libelle: 'selectionnez une valeur' }
            $cascade.data().cascade.structure = { id: 0, libelle: 'selectionnez une valeur' }
            $cascade.data().cascade.supports = [{ id: 0, libelle: 'selectionnez une valeur' }]
            $cascade.data().cascade.type_fin_vie = { id: 0, libelle: 'selectionnez une valeur' }
            $cascade.data().cascade.type_glace = { id: 0, libelle: 'selectionnez une valeur' }
            $cascade.data().cascade.id = this.cascade.id
            $cascade.data().cascade.lat = this.lat
            $cascade.data().cascade.lng = this.lng

            this.new = false
        }
        $app.data().show = true
        $app.data().showZone = false
        scrollTo(0, 45)
    })

    this.$marker.addListener('position_changed', () => {
        this.cascade.lat = this.lat
        this.cascade.lng = this.lng
        $cascade.data().cascade.lat = this.lat
        $cascade.data().cascade.lng = this.lng
    })
}

Cascade.prototype.update = function () {
    fetch(`api/cascades/${this.cascade.id}/update`, {
        method: 'post',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.cascade)
    }).then(res => res.json())
    .then(data => this.cascade = data)
    .catch(e => console.error(e))
}

Zone.prototype.addEvent = function() {
    this.$rectangle.addListener('click', () => {
        map.$maps.panTo(this.northEast)
        tlp.$tooltip.open(map.$maps, this.$rectangle)
        tlp.$tooltip.content.children[0].value = this
        tlp.$tooltip.setPosition(this.northEast)

        if (this.new == false) {
            for (const key in this.zone) {
                const el = this.zone[key]
                if (el === null) {
                    $zone.data().zone[key] = "Entrez une valeur"
                } else {
                    $zone.data().zone[key] = el
                }
            }
        } else {
            this.new = false
            $zone.data().zone.id = this.zone.id
            $zone.data().zone.nom = "Entrez une valeur"
            $zone.data().zone.latNE = this.northEastLat
            $zone.data().zone.lngNE = this.northEastLng
            $zone.data().zone.latSW = this.southWestLat
            $zone.data().zone.lngSW = this.southWestLng
        }
        $app.data().show = false
        $app.data().showZone = true
        scrollTo(0, 45)
    })

    this.$rectangle.addListener('bounds_changed', () => {
        tlp.$tooltip.setPosition(this.northEast)

        this.zone.latNE = this.northEastLat
        this.zone.lngNE = this.northEastLng
        this.zone.latSW = this.southWestLat
        this.zone.lngSW = this.southWestLng

        $zone.data().zone.latNE = this.northEastLat
        $zone.data().zone.lngNE = this.northEastLng
        $zone.data().zone.latSW = this.southWestLat
        $zone.data().zone.lngSW = this.southWestLng
    })
}

Zone.prototype.update = function() {
    fetch(`api/zones/${this.zone.id}/update`, {
        method: 'post',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(this.zone)
    }).then(res => res.json())
    .catch(e => console.error(e))
}

document.addEventListener('keydown', function(e) {
    if (document.getElementById('polygone') !== null) {
        var polygone = document.getElementById('polygone').value
        var code = (e.keyCode ? e.keyCode : e.which)
        switch (code) {
            case 13:
                polygone.valid()
                break
            case 8:

            case 46:
                polygone.delete()
                break
            case 32:
                polygone.edit()
                break
        }
    }
    drawing.$drawingManager.setDrawingMode()
})

document.querySelectorAll('.info span').forEach(el => {
    el.onclick = () => {
        clickSpan(el)
    }
})

document.querySelectorAll('strong[checkbox]').forEach(el => {
    var span = document.createElement('span')
    span.classList = 'material-icons edit'
    span.textContent = 'mode_edit'
    span.setAttribute('data-toggle', 'modal')
    span.setAttribute('data-target', '#edit')
    span.onclick = function() {
        tlp.$tooltip.close()
        let name = this.parentElement.textContent
        name = name.slice(0, name.length - 9).toLowerCase()
        $modal.data().name = name
        getFile(`api/${name}`, (data) => {
            $modal.data().collection = data
        })
    }
    el.appendChild(span)
})

function clickSpan(span) {
    edit = true

    let className = span.className
    if(span.offsetParent.classList[1] === 'zone') {
        rectangles[$zone.data().zone.id].valid()
        if (span.classList.length >= 1) {
            className= `${className} zone`
        } else {
            className= `zone`
        }
    } else {
        markers.cascades[$cascade.data().cascade.id].valid()
    }
    let value = span.textContent
    let id = span.dataset.id

    if (id.split('.').length < 2) {
        let input = document.createElement('input')

        input.dataset.id = span.dataset.id
        inputTypeNumber = ['hauteur', 'altitude_minimum', 'lat', 'lng', 'nombre_voies', 'latNE', 'lngNE', 'latSW', 'lngSW']

        if (inputTypeNumber.includes(input.dataset.id)) {
            input.type = 'number'
            if (input.dataset.id === 'lat' || input.dataset.id === 'lng') {
                moveMarker(input)
            } else if(input.dataset.id === 'latNE' || input.dataset.id === 'lngNE' || input.dataset.id === 'latSW' || input.dataset.id === 'lngSW') {
                moveRectangle(input)
            }
        }
        input.value = value
        input.classList = className

        input.addEventListener('blur', function() {
            eventInput(this)
        })
        input.addEventListener('keydown', function(e) {
            if (e.keyCode === 13) {
                this.blur()
            }
        })
        span.parentElement.appendChild(input)
        input.focus()
    } else if(id.split('.').length > 1) {
        let field
        if(id.split('.')[0] === 'niveau') {
            field = id.split('.')[0] + 'x'
        } else {
            field = id.split('.')[0] + 's'
        }
        getFile(`api/${field}`, (data)=> {
            let select = document.createElement('select')
            data.forEach(el => {
                let option = document.createElement('option')
                option.textContent = el.libelle
                option.value = el.id

                select.appendChild(option)
            })
            select.selectedIndex = $cascade.data().cascade[`${id.split('.')[0]}_id`] - 1
            span.parentElement.appendChild(select)
            select.focus()
            select.classList = className

            select.addEventListener('blur', function() {
                eventInput(this)
            })
            select.addEventListener('keydown', function(e) {
                if (e.keyCode === 13) {
                    this.blur()
                }
            })
        })
    }
    span.className += ' hidden'
}

function eventInput(input) {
    if (input.value.length != 0) {
        edit = false

        let span
        if (input.parentElement.children.length < 3) {
            span = input.parentElement.children[0]
        } else {
            span = input.parentElement.children[1]
        }

        let id = span.dataset.id.split('.')
        let f
        if(input.classList.length === 1 && input.className === 'zone' || input.classList.length > 1 && input.className.split(' ').includes('zone')) {
            f = rectangles[$zone.data().zone.id]

            $zone.data().zone[id[0]] = input.value
            if (input.type === "number") {
                f.zone[id[0]] = parseFloat(input.value)
            } else {
                f.zone[id[0]] = input.value
            }
        } else {
            f = markers.cascades[$cascade.data().cascade.id]

            if (id.length > 1) {
                f.cascade[`${id[0]}_id`] = input.selectedIndex + 1
                $cascade.data().cascade[`${id[0]}_id`] = input.selectedIndex + 1
                $cascade.data().cascade[id[0]].libelle = input.children[input.selectedIndex].text

            } else {
                $cascade.data().cascade[id[0]] = input.value
                if (input.type === "number") {
                    f.cascade[id[0]] = parseFloat(input.value)
                } else {
                    f.cascade[id[0]] = input.value
                }
            }
        }
        f.update()
        input.parentElement.removeChild(input)

        if(span.className.split(' ')[0] !== 'hidden') {
            span.className = span.className.split(' ')[0]
        } else {
            span.className = ''
        }
    }
}


function moveMarker(input) {
    input.step = 0.01
    input.oninput = function() {
        let c = markers.cascades[$cascade.data().cascade.id]
        if (input.dataset.id === 'lat') {
            c.$marker.setPosition(new google.maps.LatLng(
                input.value,
                c.lng
            ))
        } else if (input.dataset.id === 'lng') {
            c.$marker.setPosition(new google.maps.LatLng(
                c.lat,
                input.value
            ))
        }
    }
}

function moveRectangle(input) {
    input.step = 0.01
    input.oninput = function() {
        let z = rectangles[$zone.data().zone.id]
        switch(input.dataset.id) {
            case 'latNE': {
                z.$rectangle.setBounds(new google.maps.LatLngBounds(
                    z.southWest,
                    new google.maps.LatLng(input.value, z.northEastLng)
                ))
                break
            }
            case 'lngNE': {
                z.$rectangle.setBounds(new google.maps.LatLngBounds(
                    z.southWest,
                    new google.maps.LatLng(z.northEastLat, input.value)
                ))
                break
            }
            case 'latSW': {
                z.$rectangle.setBounds(new google.maps.LatLngBounds(
                    new google.maps.LatLng(input.value, z.southWestLng),
                    z.northEast
                ))
                break
            }
            case 'lngSW': {
                z.$rectangle.setBounds(new google.maps.LatLngBounds(
                    new google.maps.LatLng(z.southWestLat, input.value),
                    z.northEast
                ))
                break
            }
        }
    }
}