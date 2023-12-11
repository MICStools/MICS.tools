function [J grad] = nnCostFunction(nn_params, ...
                                   input_layer_size, ...
                                   hidden_layer_size, ...
                                   num_labels, ...
                                   X, y, lambda)
% nnCostFunction implements the neural network cost function for a three-layer
% neural network which performs classification
% [J grad] = nnCostFunction(nn_params, hidden_layer_size, num_labels, ...
% X, y, lambda) computes the cost and gradient of the neural network. The
% parameters for the neural network are "unrolled" into the vector
% nn_params and need to be converted back into the weight matrices. 
% 
% The returned parameter grad is an "unrolled" vector of the
% partial derivatives of the neural network.

% Reshape nn_params back into the parameters Theta1 and Theta2, the weight matrices
% for our three-layer neural network
Theta1 = reshape(nn_params(1:hidden_layer_size * (input_layer_size + 1)), ...
                 hidden_layer_size, (input_layer_size + 1));

Theta2 = reshape(nn_params((1 + (hidden_layer_size * (input_layer_size + 1))):end), ...
                 num_labels, (hidden_layer_size + 1));

% Setup some useful variables
m = size(X, 1);
         
J = 0;
Theta1_grad = zeros(size(Theta1));
Theta2_grad = zeros(size(Theta2));

% Part 1: Feedforward the neural network and return the cost in the
%         variable J. 
%
% Part 2: Implement the backpropagation algorithm to compute the gradients
%         Theta1_grad and Theta2_grad. We return the partial derivatives of
%         the cost function with respect to Theta1 and Theta2 in Theta1_grad and
%         Theta2_grad, respectively. 
%
%         Note: The vector y passed into the function is a vector of labels
%         containing values from 1..K. We map this vector into a 
%         binary vector of 1's and 0's to be used with the neural network
%         cost function.
%
% Part 3: Implement regularization with the cost function and gradients.
%
%         We implement this around the code for
%         backpropagation. That is, we compute the gradients for
%         the regularization separately and then add them to Theta1_grad
%         and Theta2_grad from Part 2.

X = [ones(m, 1) X];

for i=1:m
    % activations for each layer
    a1 = X(i,:)';
    z2 = Theta1 * a1;
    a2 = [1; sigmoid(z2)];
    z3 = Theta2 * a2;
    a3 = sigmoid(z3);
    % final layer activation is output vector
    h = a3;

    % create a boolean vector from a numeric label
    % yVec = (1:num_labels)' == y(i);
    % The following line is hardcoded to the case of exactly 5 labels/domains
    yVec = [(y(i,1)); (y(i,2)); (y(i,3)); (y(i,4)); (y(i,5))];
    J = J + sum(-yVec .* log(h) - (1 - yVec) .* log(1 - h));

    % backpropagation
    delta3 = a3 - yVec;
    delta2 = Theta2' * delta3 .* (a2 .* (1 - a2));
    Theta2_grad = Theta2_grad + delta3 * a2';
    Theta1_grad = Theta1_grad + delta2(2:end) * a1';
end;

% scaling cost function and gradients
J = J / m;
Theta1_grad = Theta1_grad / m;
Theta2_grad = Theta2_grad / m;

% regularization
J = J + (lambda / (2 * m)) * (sumsq(Theta1(:, 2:end)(:)) + sumsq(Theta2(:, 2:end)(:)));
Theta1_grad = Theta1_grad + (lambda / m) * [zeros(size(Theta1, 1), 1) Theta1(:,2:end)];
Theta2_grad = Theta2_grad + (lambda / m) * [zeros(size(Theta2, 1), 1) Theta2(:,2:end)];

% =========================================================================

% Unroll gradients
grad = [Theta1_grad(:) ; Theta2_grad(:)];

end
