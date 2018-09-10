import React from 'react';

const FormBreadcrumb = props => {
  return (
    <div className="mx-auto mt-5" style={{maxWidth: 700}}>
      <ul id="progressbar" >
        <li className={props.show >= 1 ? 'active' : ''} onClick={() => props.showForm(1)}>Détails de la commande</li>
        <li className={props.show >= 2 ? 'active' : ''} onClick={() => props.showForm(2)}>Choix des tickets</li>
        <li className={props.show >= 3 ? 'active' : ''} onClick={() => props.showForm(3)}>Paiement</li>
      </ul>
    </div>
  );
};

export default FormBreadcrumb;
