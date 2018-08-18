import React from 'react'
import ReactDOM from 'react-dom'
import {Container} from 'reactstrap'

import Form from './components/Form'
import Breadcrumb from './components/Breadcrumb'


class App extends React.Component {
    render(){
        return(
            <Container>
                <Breadcrumb />
                <Form />
            </Container>
        )
    }
}

ReactDOM.render(<App/>,
    document.getElementById('form'));