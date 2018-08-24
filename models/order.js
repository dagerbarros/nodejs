'use strict'
/*Se crea el esquema de como va a estar constituida la db en mongo*/
const mongoose = require('mongoose');
const schema = mongoose.Schema

const ordersSchema = schema({
	idOrder : String,
	empresa :[],
	pedido: [],
	fechRegis : {type : Date, default: Date.now()},
	status: {}
})


module.exports = mongoose.model('Order', ordersSchema);