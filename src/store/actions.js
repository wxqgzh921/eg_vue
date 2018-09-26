import * as types from './mutations_types'
// import router from '../router'

export default{
	logintip: ({commit}) => {
		commit(types.LOGINTIP)
	},
	user_signin: ({commit}, user) => {
		commit(types.USER_SIGNIN, user)
	},
	sign_btn: ({commit}) => {
		commit(types.SIGN_BTN)
	},
	user_signout: ({commit}) => {
		commit(types.USER_SIGNOUT)
	},
	cancel_signout: ({commit}) => {
		commit(types.CANCEL_SIGNOUT)
	},
	regist: ({commit}, obj) => {
		commit(types.REGIST, obj)
	},
	updatepwd: ({commit}, obj) => {
		commit(types.UPDATEPWD, obj)
	},
	forgot: ({commit}, obj) => {
		commit(types.FORGOT, obj)
	},
	livelist: ({commit}) => {
		commit(types.LIVELIST)
	},
	giftlist: ({commit}) => {
		commit(types.GIFTLIST)
	},
	yxdtvideo: ({ commit }) => {
		commit(types.YXDTVIDEO)
	},
	xxjqvideo: ({ commit }) => {
		commit(types.XXJQVIDEO)
	},
	wwszvideo: ({ commit }) => {
		commit(types.WWSZVIDEO)
	},
	mrybvideo: ({ commit }) => {
		commit(types.MRYBVIDEO)
	},
	user: ({ commit }) => {
		commit(types.USER)
	}
}
