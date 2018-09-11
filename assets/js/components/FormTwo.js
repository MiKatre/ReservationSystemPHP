import React from 'react'
import {Form, FormGroup, Row, Col, Input, Label, CustomInput, Button, UncontrolledTooltip} from 'reactstrap'
import BirthdayPicker from './BirthdayPicker'
import { formatDate } from 'react-day-picker/moment'
import posed, { PoseGroup } from 'react-pose';

import { Radio, DatePicker, TimePicker, Select, Cascader, InputNumber, Button as AntButton  } from 'antd';


const RadioButton = Radio.Button;
const RadioGroup = Radio.Group;

const Box = posed.div({
	left: { 
		x: '0%',
		staggerChildren: 100
	},
	right: {x: '10px'}
});

const CalendarAnim = posed.div({
  enter: {scale: 1},
  exit: {scale: 0}
});

class FormTwo extends React.Component {
	constructor(props) {
		super(props)
		this.state = {
			firstName: '',
			lastName: '',
			birthday: undefined,
			isFullDay: undefined,
			discount: false,
			showBirthdayPicker: false,
		}
		this.handleInputChange = this.handleInputChange.bind(this)
		this.handleBirthdayFieldClick = this.handleBirthdayFieldClick.bind(this)
		this.handleDayClick = this.handleDayClick.bind(this)
		this.handleSubmit = this.handleSubmit.bind(this)
	}

	componentDidUpdate(){
	
	}

	handleBirthdayFieldClick(){
		this.setState({
			showBirthdayPicker: !this.state.showBirthdayPicker
		})
	}

	handleDayClick(day, {selected}){
		this.setState({
			birthday: selected ? undefined : day,
			showBirthdayPicker: !this.state.showBirthdayPicker,
		})
	}

	handleInputChange(event){
		const target = event.target
		const name = target.name
		const value = target.type === 'checkbox' ? target.checked : target.value 

		this.setState({
			[name]: value,
		})
	}

	wipeState() {
		this.setState({
			firstName: '',
			lastName: '',
			birthday: undefined,
			// isFullDay: undefined,
			discount: false,
		})
	}

	handleSubmit(event){
		event.preventDefault()
		if(this.state.firstName.length !== 0 && this.state.lastName.length !== 0 && typeof this.state.birthday === 'object' && typeof this.state.isFullDay !== 'undefined') {
			const ticket = {}
			ticket.firstName = this.state.firstName
			ticket.lastName = this.state.lastName
			ticket.dateOfBirth = this.state.birthday
			ticket.isFullDay = this.state.isFullDay
			ticket.discount = this.state.discount
			this.props.handleTicketAdd(ticket)
			this.wipeState()
		}
	}

	render() {
		const date = this.state.birthday ? formatDate(this.state.birthday, 'LL', 'fr'): ''
		const ready = 
		this.state.firstName.length !== 0 
		&& this.state.lastName.length !== 0 
		&& typeof this.state.birthday === 'object' 
		&& typeof this.state.isFullDay !== 'undefined'

		return (
			<div>
				<h4 className="mb-3">
					Billets
			</h4>
	
				<Form onSubmit={this.handleSubmit} layout="vertical" className="form-container">
					<Row>
						<Col md="6" className="mb-3">
							<Label for="firstname">Prénom</Label>

							<Input type="text" name="firstName" id="firstname" placeholder="Prénom" value={this.state.firstName} onChange={this.handleInputChange}  />

						</Col>
						<Col md="6" className="mb-3">
							<Label for="lastname">Nom</Label>
							<Input type="text" name="lastName" id="lastname" placeholder="Nom" value={this.state.lastName} onChange={this.handleInputChange}  />
						</Col>
					</Row>
					<div className="mb-3">
						<label>Date de naissance</label> <br />
						<div className="d-flex justify-content-start flex-wrap-reverse" >

							<PoseGroup>
									{ this.state.showBirthdayPicker && [
										<CalendarAnim key="1">
											<BirthdayPicker 
												handleDayClick={this.handleDayClick}
												birthday={this.state.birthday}
												className="modal"
											/>
										</CalendarAnim>
									] }
							</PoseGroup>
							
							<Box pose={this.state.showBirthdayPicker ? 'right' : 'left' }>
								<Input 
									type="text" 
									placeholder="Date de naissance" 
									value={date} 
									className="mb-3" 
									onClick={this.handleBirthdayFieldClick} 
									 />
								

								<Label for="isFullDay">Type de billet</Label> <i className="fas fa-info-circle" style={{color: '#757575'}}></i>
								<br/>
								<RadioGroup onChange={this.handleInputChange} className="mb-3" name="isFullDay">
									<RadioButton value={true} >journée complète</RadioButton>
									<RadioButton value={false} >demi-journée</RadioButton>
								</RadioGroup>

								<div className="d-flex flex-row">
									<CustomInput type="checkbox" id="discount" label="Je bénéficie d'" checked={this.state.discount} name="discount" onChange={this.handleInputChange} className="mb-3"/> 
									<span style={{color:"blue"}} href="#" id="discountTooltip">une offre réduite</span>
									<UncontrolledTooltip placement="top" target="discountTooltip">
										Il sera nécessaire de présenter sa carte d’étudiant, militaire ou équivalent lors de l’entrée pour prouver qu’on bénéficie bien du tarif réduit.
									</UncontrolledTooltip>
								</div>
							</Box>
						</div>

					</div>

					<div className="text-center mt-5">
						{/* <Button disabled={!ready} className="default-btn"> Ajouter billet &#43;</Button> */}
						<AntButton type="primary" htmlType="submit" disabled={!ready} > Ajouter billet &#43;</AntButton>
					</div>
				</Form>

					
			</div>
		)
	}
}
export default FormTwo