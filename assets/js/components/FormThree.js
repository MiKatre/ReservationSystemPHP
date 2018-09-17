import React from 'react'
import {pay} from '../api'
import {
  CardNumberElement,
  CardExpiryElement,
  CardCVCElement,
  PostalCodeElement,
  Elements,
  injectStripe,
} from 'react-stripe-elements'

import {Button} from 'antd'
import {Label, Row, Col} from 'reactstrap'

const handleBlur = () => {
  console.log('[blur]');
};
const handleChange = (change) => {
  console.log('[change]', change);
};
const handleFocus = () => {
  console.log('[focus]');
};
const handleReady = () => {
  console.log('[ready]');
};

const createOptions = (fontSize, padding) => {
  return {
    style: {
      base: {
        // fontSize,
        color: '#424770',
        letterSpacing: '0.025em',
        '::placeholder': {
          color: '#6c757d',
        },
        // padding,
      },
      invalid: {
        color: '#9e2146',
      },
    },
  };
};

class _SplitForm extends React.Component {
  constructor(props) {
    super(props);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  async sendToken(payload) {
    const response = await pay(payload)
    console.log(response)
  }

  handleSubmit(ev) {
    ev.preventDefault();
    if (this.props.stripe) {
      this.props.stripe
        .createToken()
        .then((payload) => this.sendToken(payload));
    } else {
      console.log("Stripe.js hasn't loaded yet.");
    }
  };
  render() {
    return (
      <form onSubmit={this.handleSubmit}>
        <Row>
          <Col md="6">
            <Label for="cardNumber" className="stripe-label">
              Numéro de carte </Label>
              <CardNumberElement
                id="cardNumber"
                onBlur={handleBlur}
                onChange={handleChange}
                onFocus={handleFocus}
                onReady={handleReady}
                className="form-control"
                {...createOptions(this.props.fontSize)}
              />
          </Col>
        </Row>
        <Row>
          <Col md="6">
            <label className="stripe-label">
              Date d'expiration
              <CardExpiryElement
                onBlur={handleBlur}
                onChange={handleChange}
                onFocus={handleFocus}
                onReady={handleReady}
                {...createOptions(this.props.fontSize)}
              />
            </label>
          </Col>
          <Col md="4">
            <label className="stripe-label">
              Cryptogramme visuel
              <CardCVCElement
                onBlur={handleBlur}
                onChange={handleChange}
                onFocus={handleFocus}
                onReady={handleReady}
                {...createOptions(this.props.fontSize)}
              />
            </label>
          </Col>
        </Row>
        <Button type="primary" htmlType="submit" >Payer</Button>
      </form>
    );
  }
}
const SplitForm = injectStripe(_SplitForm);


export default class Checkout extends React.Component {
  constructor() {
    super();
    this.state = {
      elementFontSize: window.innerWidth < 450 ? '14px' : '18px',
    };
    window.addEventListener('resize', () => {
      if (window.innerWidth < 450 && this.state.elementFontSize !== '14px') {
        this.setState({elementFontSize: '14px'});
      } else if (
        window.innerWidth >= 450 &&
        this.state.elementFontSize !== '18px'
      ) {
        this.setState({elementFontSize: '18px'});
      }
    });
  }

  render() {
    const {elementFontSize} = this.state;
    return (
      <div>
        <h4 className="mb-3">Paiement</h4>
        <div className="form-container">
          <Elements>
            <SplitForm fontSize={elementFontSize} />
          </Elements>
        </div>
      </div>
    );
  }
}