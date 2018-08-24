'use strict'
/*todo lo basado en consultas y respuestas a la db*/

const Orders = require('../models/order');

function getOrders(req, res){
	let orderid = req.params.orderID
	Orders.findById(orderid ,(err, result) => {
		if(err) return res.status(500).send({message : `Error al conectar a la db ${err}`})
		if(!result) return res.status(404).send({message : `Orders no `})
	    res.status(200).send({order : result})
	    })
}

function getOrders(req, res){
	Orders.find({},(err, results) =>{
		if(err) return res.status(500).send({message : `Error al conectar a la db`})
	    if(!results) return res.status(404).send({message : `No existen datos archivados`})
	    res.status(200).send({orders : results})
	})
}

function postOrders(){
	

}

function putOrders(req, res){
	
}

function deleteOrders(req, res){

}

module.exports = {
	getOrders,
	getOrders,
	postOrders,
	putOrders,
	deleteOrders
}