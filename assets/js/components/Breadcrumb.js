import React from 'react';
import { Nav, NavItem, NavLink, Breadcrumb, BreadcrumbItem } from 'reactstrap';

const FormBreadcrumb = props => {
  return (
    <div>
      <Nav pills style={{maxWidth: 465, borderRadius: 6, backgroundColor: 'whitesmoke',}} className=" my-5">
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
    </div>
  );
};

export default FormBreadcrumb;
