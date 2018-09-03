import React from 'react';
import { Nav, NavItem, NavLink, Breadcrumb, BreadcrumbItem } from 'reactstrap';

const FormBreadcrumb = props => {
  return (
    <div className=" mt-4">
      {/* <Nav pills style={{maxWidth: 465, borderRadius: 6, backgroundColor: 'whitesmoke',}} className=" my-5">
        <NavItem>
          <NavLink href="#" onClick={() => props.showForm(1)} active={props.show === 1}> Détails de la commande </NavLink>
        </NavItem>
        <NavItem>
          <NavLink href="#" onClick={() => props.showForm(2)} active={props.show === 2} disabled={props.show < 2} >   
            Choix des tickets 
          </NavLink>
        </NavItem>
        <NavItem>
          <NavLink href="#" onClick={() => props.showForm(3)} active={props.show === 3} disabled={props.show < 3}>
            Paiement 
          </NavLink>
        </NavItem>
      </Nav>
      <Progress value={75}>Paiement!</Progress> */}
      <ul id="progressbar" >
        <li className={props.show >= 1 ? 'active' : ''} onClick={() => props.showForm(1)}>Détails de la commande</li>
        <li className={props.show >= 2 ? 'active' : ''} onClick={() => props.showForm(2)}>Choix des tickets</li>
        <li className={props.show >= 3 ? 'active' : ''} onClick={() => props.showForm(3)}>Paiement</li>
      </ul>
    </div>
  );
};

export default FormBreadcrumb;
