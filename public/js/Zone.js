class Zone {
    constructor() {
        let args
        if (arguments[0] instanceof google.maps.Rectangle) {
            args = { zone: arguments[1], rectange: arguments[0] }
        } else {
            args = { zone: arguments[0], rectangle: arguments[1] }
        }

        if (args.rectange === undefined) {
            fetch(`/api/zones/${args.zone.id}/dangerosite`)
            .then(res => res.json())
            .then(data => {
                let color = ''
                switch (data.niveau_danger) {
                    case 0:
                        color: '#000000'
                        break;
                    case 1:
                        color = '#33CC33'
                        break;
                    case 2:
                        color = '#FF9900'
                        break;
                    case 3:
                        color = '#FF0000'
                        break;
                }
                this.$rectangle = new google.maps.Rectangle({
                    strokeColor: color,
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: color,
                    fillOpacity: 0.35,
                    map: map.$maps,
                    bounds: new google.maps.LatLngBounds(
                        new google.maps.LatLng(args.zone.latSW, args.zone.lngSW),
                        new google.maps.LatLng(args.zone.latNE, args.zone.lngNE)
                    )
                })
                this.addEvent()
            })
        } else {
            this.$rectangle = args.rectange
            this.addEvent()
        }

        if (args.zone !== undefined) {
            this.new = false
            this.zone = args.zone
            rectangles[args.zone.id] = this
        } else {
            this.new = true
            edit = true
            fetch(`api/zones`, {
                method: 'post',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    latNE: this.northEastLat,
                    lngNE: this.northEastLng,
                    latSW: this.southWestLat,
                    lngSW: this.southWestLng
                })
            }).then(res => res.json())
                .then(data => {
                    this.zone = data
                    rectangles[this.zone.id] = this
                })
        }
    }

    get bounds() {
        return this.$rectangle.getBounds()
    }

    get northEast() {
        return this.bounds.getNorthEast()
    }

    get southWest() {
        return this.bounds.getSouthWest()
    }

    get southWestLat() {
        return this.southWest.lat()
    }

    get southWestLng() {
        return this.southWest.lng()
    }

    get northEastLat() {
        return this.northEast.lat()
    }

    get northEastLng() {
        return this.northEast.lng()
    }

    remove() {
        this.$rectangle.setMap()
        rectangles.remove(this.zone.id)
    }

    addEvent() {
        this.$rectangle.addListener('click', () => {
            map.$maps.panTo(this.northEast)
            if (this.new === false) {
                for (const key in this.zone) {
                    const el = this.zone[key]
                    if (el === null) {
                        $zone.data().zone[key] = "Entrez une valeur"
                    } else {
                        $zone.data().zone[key] = el
                    }
                }
                $app.data().show = false
                $app.data().showZone = true
                scrollTo(0, 45)
            }
        })
    }

    static check(zone) {
        let find = rectangles[zone.id] ? true : false

        if (zone.latSW > map.southWestLat && zone.latNE < map.northEastLat && zone.lngSW > map.southWestLng && zone.lngNE < map.northEastLng) {
            if (!find) new Zone(zone)
        } else {
            if (find) rectangles[zone.id].remove()
        }
    }
}