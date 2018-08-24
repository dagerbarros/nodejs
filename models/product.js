'use strict'
/*Se crea el esquema de como va a estar constituida la db en mongo*/
const mongoose = require('mongoose');
const schema = mongoose.Schema

const productsSchema = schema({
	name : String,
	photo : String,
	/*Definimos que el valos por defecto sea 0*/
	price : {type: Number, defaul: 0 },
	/*Definimos que solo una de estas categorias sean las seleccionables*/
	category : {type : String, enum: ['computers','phone','accesories']},
	description : String
})

/*exportar el squema creado "mongoose.model('nombre', el esquema que se esta utilizando) "*/
/*el nombre puede ser cualquiera*/
module.exports = mongoose.model('Product', productsSchema);