import React from 'react'
import {getRate} from '../helpers'

const Cart = ({tickets, handleRemoveTicket}) => {

	let total = 0

	const ticketList = tickets.map(ticket => {
		const rate = getRate(ticket.birthday)
		total += rate.price / 100
		const formula = ticket.formula === 'full' ? 'Journée complète' : 'Demi-journée'
		return (
			<Ticket 
				price={rate.price / 100} 
				rateName={rate.name} 
				name={`${ticket.firstName.slice(0, 1).toUpperCase()}. ${ticket.lastName}`} 
				formula={formula} 
				handleRemoveTicket={handleRemoveTicket}
				id={ticket.id}
				key={ticket.id}
			/>
		)
	})

 	return (
	<div>
		<h4 className="d-flex justify-content-between align-items-center mb-3">
			<span className="text-muted">Panier</span>
			<span className="badge badge-secondary badge-pill"> {tickets.length} </span>
		</h4>

		<ul className="list-group mb-3" >
			{ticketList}
			<li className="list-group-item d-flex justify-content-between">
				<span>Total (EUR)</span>
				<strong>{total}€</strong>
			</li>
		</ul>
	</div>
	)

}

const Ticket = props => (
		<li className="list-group-item d-flex justify-content-between lh-condensed">
		<div>
			<h6 className="my-0">Billet {props.rateName} - <small>{props.name}</small>
				{/* <a href="#" className="badge badge-light">Éditer</a>  */}
			</h6>
			<small className="text-muted"> {props.formula} </small>
		</div>
		<div className="d-flex flex-column" >
			<div style={{marginTop: '-.6rem'}} className="text-right">
				<span 
					onClick={() => props.handleRemoveTicket(props.id)}
					className="text-danger remove-ticket" 
					style={{fontSize: 20}}
				>
					&#215;
				</span>
			</div>
			<span className="text-muted">{props.price}€</span>
		</div>
	</li>

);
	// <li className="list-group-item d-flex justify-content-between bg-light">
	// 	<div className="text-success">
	// 		<h6 className="my-0">Réduction</h6>
	// 		<small>Document à présenter à l'accueil</small>
	// 	</div>
	// 	<span className="text-success">-5€</span>
	// </li>
	// <li className="list-group-item d-flex justify-content-between">
	// 	<span>Total (EUR)</span>
	// 	<strong>20€</strong>
	// </li>


export default Cart