'use strict'

const jwt = require('jwt-simple')
const moment = require('moment')
const config = require('../config')

function createToken(user){
	const payloadjwt = {
		sub: user._id,
		iat: moment().unix(), 
		exp: moment().add(10, 'days').unix()
	}

	return jwt.encode(payloadjwt,config.key_passwd)
}

function decodeToken(token){
	const decoded = new Promise((resolve, reject) =>{
		try{
			const payload = jwt.decode(token, config.key_passwd)

			if(payload.exp <= moment().unix()){
				reject({
				status: 401,
				message : 'Expired token'
			})

			}
			resolve(payload.sub)

		}catch (err){
		reject({
				statusObj: 500,
				message : 'Invalid token'
			})
		}
	})
	return decoded
}

module.exports = {
	createToken,
	decodeToken
}