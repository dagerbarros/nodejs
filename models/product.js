'use strict'
/*Se crea el esquema de como va a estar constituida la db en mongo*/
const mongoose = require('mongoose');
const schema = mongoose.Schema

const productsSchema = schema({
	name : String,
	photo : String,
	price : {type: Number, defaul: 0 },
	category : {type : String, enum: ['computers','phone','accesories']},
	description : String
})

module.exports = mongoose.model('Product', productsSchema);