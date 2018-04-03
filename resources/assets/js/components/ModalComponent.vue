<template>
  <!-- Modal -->
    <div id="edit" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Modification des {{ name }}</h4>
                </div>
                <form>
                    <div class="modal-body">
                        Sélectionnez les {{ name }} :
                        <ul>
                            <li v-for="d in collection" class="li-modal">
                                <input type="checkbox" name="data" v-bind:value="d.id" :checked="check(d.id)" ref="inputs" v-bind:data-libelle="d.libelle" v-on:change="checkPoids">
								{{ d.libelle}}
								<input type="number" v-if="name === 'constituants'" v-on:input="checkPoids" class="poids-constituant" placeholder="		%" min="0" max="100">
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal" name="modifier" v-on:click="updateData()">Modifier</button>
                        <button type="button" class="btn btn-error" data-dismiss="modal">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


<script>
var data = {
  name: "",
  collection: [
    {
      id: 0,
      libelle: ""
    }
  ]
};

export default {
	name: "modal",
  	data() {
	    return data;
  	},
  	methods: {
	    check(id) {
			if (typeof $cascade !== "undefined") {
				find = false;
				$cascade.data().cascade[this.name].forEach(el => {
					if (el.id === id) {
						find = true;
					}
				})
				return find
			}
		},
		updateData() {
			if(this.name === 'constituants') {
				if(this.required()) return
			}
			fetch(`api/cascades/${$cascade.data().cascade.id}/${this.name}/update`, {
				method: "POST",
				headers: {
      				'Content-Type': 'application/json'
    			},
    			body: JSON.stringify(this.getChecked())
			}).then(res => res.json())
			.then(data => $cascade.data().cascade[this.name] = data).catch(e => console.error(e))
		},
		getChecked() {
			let inputs = Array.prototype.slice.call(document.querySelectorAll('input[type="checkbox"]:checked'))
			if(this.name === 'constituants') {
				return inputs.map(el => {
					return {
						id: el.value,
						poids: el.nextElementSibling.value
					}
				})
			} else {
				let inputs = Array.prototype.slice.call(document.querySelectorAll('input[type="checkbox"]:checked'))
				return inputs.map(el => {
					return el.value
				})
			}
		},
		required() {
			let inputs = Array.prototype.slice.call(document.querySelectorAll('input[type="checkbox"]:checked'))
			let error = false
			let btnDismiss = inputs[0].form.modifier
			inputs.forEach(el => {
				let inputPoids = el.nextElementSibling
				let spanError = document.createElement('span')
				spanError.textContent = 'Veuillez remplir ce champs !'
			
				if(inputPoids.value === '' || inputPoids.value === '0') {
					if(inputPoids.nextElementSibling === null) {
						inputPoids.after(spanError)
					}
					error = true
				} else {
					if(inputPoids.nextElementSibling !== null) {
						inputPoids.nextElementSibling.remove()
					}
				}
			})
			if(error) {
				btnDismiss.dataset.dismiss = ''
			} else {
				btnDismiss.dataset.dismiss = 'modal'
			}
			return error
		},
		checkPoids() {
			if(this.name === 'constituants') {
				let notChecked = Array.prototype.slice.call(document.querySelectorAll('input[type="checkbox"]:not(:checked)'))
				notChecked.forEach(el => {
					el.nextElementSibling.value = ''
				})

				let inputs = Array.prototype.slice.call(document.querySelectorAll('input[type="checkbox"]:checked'))
				let total = 0
				let inputOther
				this.$refs.inputs.forEach(el => {
					if(el.dataset.libelle === 'autres') {
						inputOther = el.nextElementSibling
					}
				})
				inputs.forEach(el => {
					let inputPoids = el.nextElementSibling
					if(inputPoids.value !== '') {
						if(inputPoids.nextElementSibling !== null) {
							inputPoids.nextElementSibling.remove()
						}
						total += parseFloat(inputPoids.value)
					}
				})
				let btnUpdate = inputs[0].form.modifier
				if(total < 100) {
					inputOther.value = 100 - total
					if(btnUpdate.previousElementSibling !== null) {
						btnUpdate.previousElementSibling.remove()
					}
					btnUpdate.disabled = false
				} else if(total > 100) {
					let errorPoids = document.createElement('span')
					errorPoids.textContent = 'Le poids de tous les éléments dépasse 100%'
					if(btnUpdate.previousElementSibling === null) {
						btnUpdate.before(errorPoids)
						btnUpdate.disabled = true
					}
					inputOther.value = ''
				} else {
					inputOther.value = ''
					if(btnUpdate.previousElementSibling !== null) {
						btnUpdate.previousElementSibling.remove()
					}
					btnUpdate.disabled = false
				}
			}
		}
	},
	updated() {
		this.$refs.inputs.forEach(el => {
			if(el.dataset.libelle === 'autres') {
				el.disabled = true
				el.nextElementSibling.readOnly = true
			}
		})
	}
}
</script>
