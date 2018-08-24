'use strict'
/*Modulo basado en las rutas o Endpoins*/

const express = require('express')
const api = express.Router()
const UserCtrl = require('../controllers/user')
const CntrlProduct = require('../controllers/product');
const CntrlOrder = require('../controllers/orders');
const auth = require('../middlewares/auth')

/*este llamado al middleware verifica el acceso de la persona en cuanto a permiso*/

/*routes CRUD of products*/
api.get('/products',CntrlProduct.getProducts);

api.get('/products/:productID', CntrlProduct.getProduct)

api.post('/products', CntrlProduct.postProduct)

api.put('/products/:productID',auth,CntrlProduct.putProduct)

api.delete('/products/:productID',auth, CntrlProduct.deleteProduct)
/* routes CRUD of order*/
api.get('/orders',CntrlOrder.getOrders);

api.get('/orders/:orderID', CntrlOrder.getOrders)

api.post('/orders', CntrlOrder.postOrders)

api.put('/orders/:orderID',auth,CntrlOrder.putOrders)

api.delete('/orders/:orderID',auth, CntrlOrder.deleteOrders)

api.post('/signup', UserCtrl.signUp)

api.post('/signin', UserCtrl.signIn)

api.get('/private', auth, (req, res) => {
	res.status(200).send({message : 'access confir'})
})

module.exports = api
