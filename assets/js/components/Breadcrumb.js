import React from 'react';
import { Breadcrumb, BreadcrumbItem } from 'reactstrap';

const FormBreadcrumb = (props) => {
  return (
    <div>
      {/* <Breadcrumb>
        <BreadcrumbItem active>Commande </BreadcrumbItem>
        <BreadcrumbItem><a href="#">Tickets </a></BreadcrumbItem>
        <BreadcrumbItem ><a href="#">Paiement </a></BreadcrumbItem>
      </Breadcrumb> */}
      <ul className="nav nav-pills card-header-pills mx-auto my-5" style={{maxWidth: 500}}>
        <li className="nav-item">
          <a className="nav-link active" href="#">Détails de la commande</a>
        </li>
        <li className="nav-item">
          <a href="#" className="nav-link disabled">Choix des tickets</a>
        </li>
        <li className="nav-item">
          <a className="nav-link disabled" href="#">Paiement</a>
        </li>
      </ul>
    </div>
  );
};

export default FormBreadcrumb;
