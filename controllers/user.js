/*Controlador que se encargara del registro y autentuificacion del usuario*/
'use strict'

const User = require('../models/user')
const service = require('../services')
const bcrypt = require('bcrypt-nodejs')

function signUp (req, res){
	const user = new User({
		email: req.body.email,
		name: req.body.name,
		password: req.body.password
	})

	user.save((err) =>{
		if(err) return res.status(500).send({message : `Error al guardar registro`})
		return res.status(200).send({token : service.createToken(user)})
	})
}

function signIn(req, res){
	const passwd= req.body.password;
	User.find({email : req.body.email}, function (err, user) {
		if(err) return res.status(500).send({message: 'Erro al establecer conexion'})
		if(user=='') return res.status(403).send({message: 'Usuario no registrado'})
			//console.log(user[0]['password'])
			const isMatch = bcrypt.compareSync(passwd,user[0]['password']);
			if(!isMatch) return res.status(404).send({message: 'error al verificar password'})
	    	req.user = user
	    	res.status(200).send({message: 'Login successful',token: service.createToken(user)})
	})
}

module.exports = {
	signUp,
	signIn
}/*$2a$10$z3R20JeHYolo20nK.kwb8.F4p1fhgXDaG3GhoLaKnImc7sFniUFZ2*/
