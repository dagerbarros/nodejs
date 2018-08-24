'use strict'
/*todo lo basado en consultas y respuestas a la db*/

const Products = require('../models/product');

function getProduct(req, res){
	let productid = req.params.productID
	Products.findById(productid ,(err, result) => {
		if(err) return res.status(500).send({messaje : `Error al conectar a la db ${err}`})
		if(!result) return res.status(404).send({messaje : `Producto no encontrado`})
		/*en el el caso de objetos en ES6 se coloca la clave igual que el valor(res.status(200).send({product}))*/
	    res.status(200).send({product : result})
	    })
}

function getProducts(req, res){
	Products.find({},(err, results) =>{
		if(err) return res.status(500).send({messaje : `Error al conectar a la db`})
	    if(!results) return res.status(404).send({messaje : `No existen datos archivados`})
	    res.status(200).send({products : results})
	})
}

function postProduct(){
	console.log('POST /api/product')
	console.log(req.body)
	/*se crea una instancia al objeto*/
	let products = new Products()
	products.name = req.body.name
	products.photo = req.body.photo
	products.price = req.body.price
	products.category = req.body.category
	products.description = req.body.description
	/*Con el metodo save se procede a insertar en la db*/
	products.save((err,productStored) => {
		if(err){
			res.status(500).send({messaje : `Error al guardar el porducto ${err}`})
		}
		res.status(200).send({messaje : 'Productos Guardado'})
	})

}

function putProduct(req, res){
	let productid = req.params.productID
	let update = req.body
	Products.findByIdAndUpdate(productid, update, {new : true}, (err, result) => {
		if(err) return res.status(500).send({messaje : `Error al actualizar el producto ${err}`})
	    res.status(200).send({product : result})
	})
}

function deleteProduct(req, res){
		let productid = req.params.productID
	Products.findByIdAndRemove(productid, (err) => {
		if(err) return res.status(500).send({messaje : `Error al buscar el producto ${err}`})
		res.status(200).send({messaje : `Producto eliminado con exito`})
	})
}

module.exports = {
	getProduct,
	getProducts,
	postProduct,
	putProduct,
	deleteProduct
}