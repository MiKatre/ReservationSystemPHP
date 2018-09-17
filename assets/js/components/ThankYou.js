import React from 'react'
import {Row, Col, Icon} from 'antd'

const ThankYou = props => {

  // let reservationCode = 'UHY872O8'
  return (
    <Row >
      <Col xs={{span: 24, offset: 0}} md={{span: 20, offset: 2}} className="form-container">
        <div className="d-flex flex-column flex-sm-row">
          <div className="mr-sm-4 text-center">
            <Icon type="check-circle" theme="twoTone" twoToneColor="#52c41a" style={{ fontSize: '40px' }}/>
          </div>
          <div>
            <h2>Merci pour votre commande !</h2>
            <p>Une commande a été passée au nom de <strong>{props.firstName} {props.lastName}</strong></p>
            <p>Votre code de réservation est <strong>{props.reservationCode}</strong></p>
            <p>Un email de confirmation a été envoyé à l'adresse <strong>{props.email}</strong>.</p>
            <p>Celui-ci contient vos billets. Vous pouvez les imprimer ou montrer l'email à l'accueil. </p>
            <p>Si vous bénéficiez d'un tarif réduit, veillez à vous munir des documents correspondants.</p>
          </div>
        </div>
      </Col>
    </Row>
  )
}

export default ThankYou